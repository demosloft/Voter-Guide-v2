<?php
add_action('init','save_fields');
function save_fields(){

    if(isset($_REQUEST['page'])=='dab-settings' && isset($_POST['dab_submit'])){
        $data =  $_POST['fields'] ;
        update_option('dab_field_option_name','');
        update_option('dab_field_option_name',$data);
    }

}

function dab_add_meta_box() {
    $screens = array( 'site' );
    foreach ( $screens as $screen ) {
        add_meta_box(
            'dab',
            'User info',
            'dab_show_custom_meta_box',
            'voter-guide',
            'normal',
            'high'
        );
    }
}

add_action( 'add_meta_boxes', 'dab_add_meta_box' );
function dab_show_custom_meta_box( $post ) {
    wp_nonce_field( 'dab', 'dab_nonce' );
    $dabdivision = get_post_meta( $post->ID, 'dab_division', true );
    $name = get_post_meta( $post->ID, 'dab_name', true );
    $dab_email = get_post_meta( $post->ID, 'dab_email', true );
    $office = get_post_meta( $post->ID, 'dab_office', true );
    $dab_state = get_post_meta( $post->ID, 'dab_state', true );
    $dab_img = get_post_meta( $post->ID, 'dab_img', true );
    $dab_election = get_post_meta( $post->ID, 'dab_election', true );
    $dab_status = get_post_meta( $post->ID, 'dab_status', true );
    $rating = get_post_meta( $post->ID, 'dab_rating', true );
    $address = get_post_meta( $post->ID, 'dab_address', true );
    $zip = get_post_meta( $post->ID, 'dabzipcode', true );
    if(empty($dab_img)){ $iconURL= VOTER_GUIDE_PLUGIN_PATH_URL .'assets/img/dummy.png';  }else{ $iconURL=  $dab_img; }
    $banner_img = get_post_meta($post->ID,'post_banner_img',true);
    $loader= VOTER_GUIDE_PLUGIN_PATH_URL .'assets/img/loader.gif';
    $banner_eeeimg = get_post_meta($post->ID,'post_banner_img',true);
    if(is_array($banner_img)){ $banner_img ='';}else{ $banner_img = $banner_img; }

    ?>
    <div class="main_form-3 dab-voder-guid">
        <table width="100%" cellpadding="10px" cellspacing="0" border="0">
            <tr>
                <td width="20%">Name</td>
                <td width="80%"><input placeholder="" value="<?php echo $name; ?>" id="dab_name" name="dab_name" type="text" required /></td>
            </tr>
            <tr>
                <td width="20%">Email</td>
                <td width="80%"><input placeholder="Enter your email" value="<?php echo $dab_email; ?>" id="dab_email" name="dab_email" type="email" required /></td>
            </tr>
            <tr>
                <td width="20%">Street</td>
                <td width="80%"><input placeholder="Street Address" value="<?php echo $address; ?>" id="dab_address" name="dab_address" type="text" required /></td>
            </tr>        <tr>
                <td width="20%">Zip</td>
                <td width="80%"><input id="dabzip_code" name="dabzipcode" type="number" value="<?php echo $zip; ?>" placeholder="Enter Zip code" maxlength="5" /> </td>
            </tr>
            <tr>
                <td >State</td>
                <td >
                    <select id="dab_state" name="dab_state" required>
                        <?= voter_guide_render_state_options($dab_state); ?>
                    </select>
                </td>
            </tr>

            <tr>
                <td>Office</td>
                <td><input type="hidden" id="posthidden" value="<?php echo get_the_ID(); ?>">  <select name="dab_office" id="dab_office" selecte-ddata="<?php echo $office; ?>" required> <option value=""  >Select Office</option> </option> </select> <input type="hidden" name="dab-division" id="dab-division" value="<?php echo $dabdivision; ?>">
                    <img id="searchdab" style="display:none;"src="<?php echo $loader; ?>" width="30"> </td>
            </tr>
            <tr class="photo">

                <td>Photo</td>
                <td class="photo-insert"><?php echo multi_media_uploader_field( 'post_banner_img', $banner_img ); ?></td>

            </tr>
            <tr>
            <tr class="photo">
            <tr>
                <td>Election</td>
                <td>
                    <select name="dab_election" id="dab_election" required>
                        <option value="2021"<?php echo ($dab_election == '2021')?"selected":"" ?> >2021 </option>
                        <option value="2022"<?php echo ($dab_election == '2022')?"selected":"" ?> >2022</option>
                        <option value="2023"<?php echo ($dab_election == '2023')?"selected":"" ?> >2023</option>
                        <option value="2024"<?php echo ($dab_election == '2024')?"selected":"" ?> >2024</option>
                        <option value="2025"<?php echo ($dab_election == '2025')?"selected":"" ?> >2025</option>
                    </select>
                </td>
            </tr>

            <tr>
                <td>Status</td>
                <td>
                    <select name="dab_status" id="dab_status">
                        <option value="Approved" <?php echo ($dab_status == 'Approved')?"selected":"" ?> >Approved</option>
                        <option value="Pending"<?php echo ($dab_status == 'Pending')?"selected":"" ?>>Pending</option>
                        <option value="Decline" <?php echo ($dab_status == 'Decline')?"selected":"" ?>>Decline</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Rating</td>
                <td><input placeholder="Rating" value="<?= $rating; ?>" id="dab_rating" name="dab_rating" type="number" size="4" maxlength="3" onchange="rating_change_handler"></td>
            </tr>

            <?php

            $quednary  = get_option('dab_field_option_name');
            $count = count( $quednary);
            if($count >0){
                $i = 1;
                $a =  $quednary;
                foreach( $a as $key=>$fields ){
                    $a[$i]['field_value'] = get_field_value_dab($i);

                    $i++;
                }
                $i = 1;
                foreach( $a as $fields ){
                    if(array_key_exists("field_options",$fields)){
                        $field_option =$fields['field_options'] ;
                    }else{
                        $field_option = ''; }
                    $field_type = $fields['field_type'] ;
                    $selected = $fields['field_value'];
                    $field_position = $fields['field_position'];
                    $fieldname=$fields['field_name'];
                    if (!empty($fields['field_name'])) {
                        $value = $fields['field_value'];
                        $newarry[]= array(
                            'field_type'=>$field_type ,
                            'field_option'=>  $field_option,
                            'value'=> $value,
                            'index'=> $i,
                            'selected'=> $selected,
                            'field_position'=> $field_position,
                            'fieldname'=> $fieldname,
                        );
                        ?>

                        <?php $i++; }  }}
            /* filter the data with field_position */
            usort($newarry, function($a, $b) {
                $retval = $a['field_position'] <=> $b['field_position'];

                return $retval;
            });

            echo create_fields($newarry);
            ?>

            <tr>

            </tr>
        </table>
    </div>

    <?php
}
function create_fields($newarry){
    $output ='';

    foreach( $newarry as $array){

        $field_type = $array['field_type'];
        $field_option = $array['field_option'];
        $i = $array['index'];
        $value = $array['value'];
        $selected = $array['selected'];
        $field_position = $array['field_position'];
        $fieldname = $array['fieldname'];

        if($field_type == 'text'){ if(is_array($value)){ $value = ''; }
            $field_name= "fields[{$i}][field_value]";
            @$output .= '<tr data-id='.$field_position.'>  <td colspan="2" > <div class="quednary" style="display:flex">   <strong > Question:</strong><p style="margin: 0 0 15px;">'. $fieldname.'</p>  </div>
                 <p><input type="'.$field_type.'" name="'.$field_name.'" value="'.$value.'" required>    </p>  </td>  </tr>';

        }else if($field_type == 'select'){

            @$output  .= '<tr data-id='.$field_position.'>  <td colspan="2" > <div class="quednary" style="display:flex">   <strong > Question:</strong><p style="margin: 0 0 15px;">'. $fieldname.'</p> </div>';
            @$output  .=  "<select name='fields[{$i}][field_value]' required >";
            $options = $field_option;
            $option  = explode(',',$options);
            foreach($option as $value){
                $s = ($selected == $value)?'selected':'';
                @$output .=  "<option value='{$value}' {$s}>{$value}</option>";

            }
            $output .= "</select> </td>  </tr>";
        }else if($field_type == 'checkbox'){
            $options = $field_option;

            if(is_array($selected)){ $slect = $selected; }else{ $slect = array($selected); }
            $option  = explode(',',$options);
            $s = '';
            if(is_array($option)){
                foreach($option as $value){
                    if (!empty($slect)) {

                        if ( in_array($value, $slect) ){	$s = 'checked'; }else{ $s = ''; } }
                    @$output .= '<tr data-id='.$field_position.' ><td colspan="2" > <div class="quednary" style="display:flex">   <strong  > Question:</strong><p style="margin: 0 0 15px;">'. $fieldname.'</p>  </div>
                 <p>';
                    @$output .= "<div class='dab-radio dab-checkbox'><input type='checkbox'  name='fields[{$i}][field_value][]' value='{$value}' {$s} >{$value}</div>  </p></td>  </tr> ";
                }
            }
        }else if($field_type == 'textarea'){ if(is_array($value)){ $value = ''; }
            @$output .= '<tr data-id='.$field_position.' ><td colspan="2" > <div class="quednary" style="display:flex">   <strong  > Question:</strong><p style="margin: 0 0 15px;">'. $fieldname.'</p>  </div>
                 <p>';
            @$output .=  "<textarea id='' name='fields[{$i}][field_value]' placeholder='Type your message here...' required >$value</textarea>  </p></td>  </tr> ";
        }

    }

    return   $output;
}

function get_field_value_dab($key){
    $id = get_the_ID();
    $value = '';
    $data = get_post_meta($id, 'dab_fields_data', false );
    if(!empty($data)){
        foreach($data[0] as $keys => $values ){

            if($key == $keys){
                $value = $values['field_value'];
            }
        }
    }
    return $value;
}

function dab_save_meta_box_data( $post_id ) {
    if ( ! isset( $_POST['dab_nonce'] ) ) {
        return;
    }

    if ( ! wp_verify_nonce( $_POST['dab_nonce'], 'dab' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
        if ( ! current_user_can( 'edit_page', $post_id ) ) {
            return;
        }
    } else {
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
    }
    if ( ! isset( $_POST['dab_name'] ) ) {
        return;
    }

    update_post_meta( $post_id, 'dab_division',  $_POST['dab-division']) ;
    update_post_meta( $post_id, 'dab_fields_data',  $_POST['fields']) ;
    update_post_meta( $post_id, 'dab_address', sanitize_text_field( $_POST['dab_address'] ) );

    update_post_meta( $post_id, 'dabzipcode', sanitize_text_field( $_POST['dabzipcode']));

    update_post_meta( $post_id, 'dab_email', sanitize_text_field( $_POST['dab_email'] ) );
    update_post_meta( $post_id, 'dab_name', sanitize_text_field( $_POST['dab_name'] ) );
    update_post_meta( $post_id, 'dab_rating', sanitize_text_field( $_POST['dab_rating'] ) );

    update_post_meta( $post_id, 'dab_office', sanitize_text_field( str_replace("'", "",$_POST['dab_office'])));

    update_post_meta( $post_id, 'dab_state', sanitize_text_field( $_POST['dab_state'] ) );

    update_post_meta( $post_id, 'dab_election', sanitize_text_field( $_POST['dab_election'] ));

    update_post_meta( $post_id, 'dab_status', sanitize_text_field( $_POST['dab_status'] ));

    get_voter_excerpt($post_id);

    if( isset( $_POST['post_banner_img'] ) ){ set_post_thumbnail( $post_id, $_POST['post_banner_img'] );
        update_post_meta( $post_id, 'post_banner_img', $_POST['post_banner_img'] );
    }

}
add_action( 'save_post', 'dab_save_meta_box_data' );

function wporg_filter_title( $output ) {
    return 'The ' . $output . ' was filtered';
}
add_filter( 'custom_filter', 'wporg_filter_title' );
function questionnaire_fields($field_type, $field_name,$field_option,$index,$field_position){
    if($field_type == 'text' || $field_type == 'textarea'){ $field = ''; }else{
        $field = "<input type='text' value='{$field_option}' class='dabquestionnaire' name='fields[{$index}][field_options]'>";
    } ?>
        <tr class='row-<?php echo $index; ?> test' data-tr='<?php echo $index; ?>'>
            <td>
                <p class='dab-questions'>Question:</p>
                <input type='text' class='dabquestionnaire' name='fields[<?php echo $index; ?>][field_name]' value='<?php echo $field_name; ?>' />

                <select name='fields[<?php echo $index; ?>][field_type]' data-id='<?php echo $index; ?>' data-tr='row-<?php echo $index; ?>' class='select_type'>

                    <option value='text'  <?php echo ($field_type == 'text')?"selected":"" ?>>Text </option>
                    <option value='select'  <?php echo ($field_type == 'select')?"selected":"" ?>  >Select </option>
                    <option value='checkbox'  <?php echo ($field_type == 'checkbox')?"selected":"" ?> >Checkbox </option>
                    <option value='textarea' <?php echo ($field_type == 'textarea')?"selected":"" ?>  >Textarea </option>
                </select>
                <input type="hidden" class="rwo_field"   name="fields[<?php echo $index; ?>][field_row]"value="row-<?php echo $index; ?>" />

                <input type="text" class="rwo_field dabquestionnaire" placeholder="Set order"  name="fields[<?php echo $index; ?>][field_position]"value="<?php echo $field_position; ?>" />
                <div class='dab_field'><?php echo $field; ?></div> <a class='button dab-remove-row'  href='#'>-</a>
            </td>
        </tr>
    <?php
}

function add_meta_row_dab($count){
    $count = $count +1; ?>
        <tr class='empty-row screen-reader-text que_row row-<?= $count; ?>' data-tr='<?= $count; ?>'>
            <td>
                <p class='dab-questions next'>Question:</p>
                <input type='text' class='dabquestionnaire' id='field_name_<?= $count; ?>' name='fields[<?= $count; ?>][field_name]' disabled />
                <select name='fields[<?= $count; ?>][field_type]' data-id='<?= $count; ?>' data-tr='row-<?= $count; ?>' id='field_type_<?= $count; ?>' class='select_type' disabled>
                    <option value='text'>Text </option>
                    <option value='select'>Select </option>
                    <option value='checkbox'>Checkbox </option>
                    <option value='textarea'>Textarea </option>
                </select>
                <input type='hidden' class='rwo_field' name='fields[<?= $count; ?>][field_row]' value='row-<?= $count; ?>' disabled />
                <input type='text' class='field_position dabquestionnaire' placeholder='Set order' name='fields[<?= $count; ?>][field_position]' value='<?= $count; ?>'  disabled/>
                <div class='dab_field'></div>
                <a class='button dab-remove-row'  href='#'>-</a>
            </td>
        </tr>
    <?php
}

add_filter('manage_edit-voter-guide_columns',   'add_admin_questionnaire_columns');
function add_admin_questionnaire_columns($columns)
{
    $columns = array(
        'cb' => $columns['cb'],
        'image' => __( 'Photo' , 'dab' ),
        'title' => __( 'Name' , 'dab' ),
        'office' => __( 'Office', 'dab' ),
        'state' => __( 'State', 'dab' ),
        'rating' => __( 'Rating', 'dab' ),
        'status' => __( 'Status', 'dab' ),
        'approvestatus' => __( 'Approve', 'dab' ),
    );
    return $columns;
}

function product_custom_column_values( $column, $post_id ) {
    if ($column == 'image') {
        $banner_img = get_post_meta($post_id,'post_banner_img',true);

        $iconURL= VOTER_GUIDE_PLUGIN_PATH_URL .'assets/img/dummy.png';
        $url =  wp_get_attachment_url($banner_img);
        if(empty($url)){ $url = 	$iconURL; }
        echo 	"<img src='{$url}' width='50'>";
    }
    if ($column == 'office') {
        echo 	get_post_meta($post_id, 'dab_office',  true);
    }
    if ($column == 'approvestatus') {
        $status = 	get_post_meta($post_id, 'dab_status',  true);
        $loader= VOTER_GUIDE_PLUGIN_PATH_URL .'assets/img/loader.gif';
        $checked = "";
        if($status == 'Approved'){ $checked = "checked"; $sta = "Pending"; $hold = "Approved"; } else{  $sta = "Approved"; $hold  ="Pending";  }
        echo "<input type='checkbox' hold-status='$hold' data-id ='$post_id'name='approvestatus' class='approvests' id='$post_id-approvests' value='$sta' $checked>";
        echo '<img id="'.$post_id.'_adsearchdab"style="display: none;" src="'.$loader.'" width="30">';
    }
    if ($column == 'state') {
        echo 	get_post_meta($post_id, 'dab_state',  true);
    }
    if ($column == 'rating') {
        echo 	get_post_meta($post_id, 'dab_rating',  true);
    }
    if ($column == 'status') {
        echo 	get_post_meta($post_id, 'dab_status',  true);
    }

}
add_action( 'manage_voter-guide_posts_custom_column' , 'product_custom_column_values', 10, 2 );

function multi_media_uploader_field($name, $value = '') {
    $image = '">Add Media';
    $image_str = '';
    $image_size = 'full';
    $display = 'none';
    $value = explode(',', $value);

    if (!empty($value)) {
        foreach ($value as $values) {
            if ($image_attributes = wp_get_attachment_image_src($values, $image_size)) {
                $image_str .= '<li data-attechment-id=' . $values . '><a href="' . $image_attributes[0] . '" target="_blank"><img src="' . $image_attributes[0] . '" /></a></li>';
            }
        }

    }

    if($image_str){
        $display = 'inline-block';
    }

    return '<div class="multi-upload-medias"><ul>' . $image_str . '</ul><a href="#" class="wc_multi_upload_image_button button' . $image . '</a><input type="hidden" class="attechments-ids ' . $name . '" name="' . $name . '" id="' . $name . '" value="' . esc_attr(implode(',', $value)) . '" /><a href="#" class="wc_multi_remove_image_button button" style="display:inline-block;display:' . $display . '">Remove media</a></div>';
}

function create_front_fields($newarry){
    $checked ='';
    $output ='';
    $x=1;
    foreach( $newarry as $array){

        $field_type = $array['field_type'];
        $field_option = $array['field_option'];
        $index = $array['index'];
        $field_name = $array['field_name'];

        if($field_type == 'text'){
            $output .='<tr> <td colspan="2" > <div class="quednary" style="display:flex">      <strong style="width:108px"> Question '.$x.':</strong><p style="margin: 0 0 15px;"> '.$field_name.'</p></div> <p> ';
            $output .=  "<input type='{$field_type}' name='fields[{$index}][field_value]' value='' required> ";
            $output .=   '</p>         </td>      </tr>';
        }else if($field_type == 'select'){
            $output .='<tr> <td colspan="2" > <div class="quednary" style="display:flex">      <strong style="width:108px"> Question '.$x.':</strong><p style="margin: 0 0 15px;"> '.$field_name.'</p></div> <p> ';
            $output  .=  "<select name='fields[{$index}][field_value]'><option></option>";
            $options = $field_option;
            $option  = explode(',',$options);
            foreach($option as $value){

                $output .=  "<option value='{$value}' >{$value}</option>";

            }
            $output .= "</select> </p>         </td>      </tr>";

        }else if($field_type == 'checkbox'){

            $options = $field_option;
            $option  = explode(',',$options);
            $s = '';
            $output .='<tr> <td colspan="2" > <div class="quednary" style="display:flex">      <strong style="width:108px"> Question '.$x.':</strong><p style="margin: 0 0 15px;"> '.$field_name.'</p></div> <p> ';
            foreach($option as $value){
                $output .= "<div class='dab-radio dab-checkbox'><input type='checkbox'  name='fields[{$index}][field_value][]' value='{$value}' required ><span>{$value}</span></div>";
            }
            $output .=   '</p>         </td>      </tr>';
        }else if($field_type == 'textarea'){
            $output .='<tr> <td colspan="2" > <div class="quednary" style="display:flex">      <strong style="width:108px"> Question '.$x.':</strong><p style="margin: 0 0 15px;"> '.$field_name.'</p></div> <p> ';
            $output .=  "<textarea id='' name='fields[{$index}][field_value]' placeholder='Type your message here...' required></textarea> "  ;
            $output .=   '</p>         </td>      </tr>';
        }
        $x++;
    }
    return $output;

}

add_action( 'wp_ajax_dab_get_api_data', 'dab_get_api_data' );
add_action( 'wp_ajax_nopriv_dab_get_api_data', 'dab_get_api_data' );

// get search results
function dab_get_api_data(){

    $zip =  $_POST['zip'];
    $dab_state =  $_POST['state'];
    $dabname =  $_POST['dabname'];
    $dabfull_add =  $_POST['dabfull_add'];
    $output = '';
    if(!empty($zip) || !empty($dabfull_add) ) {
        $dab = get_google_api($dabfull_add, $zip, $dab_state);
        $office_list = $dab->offices;
        foreach($office_list as $offices){
            $name = $offices->name; $office_array[] =  $name ;
            $divisionId[] = $offices->divisionId;
        }
    }

    $output .= '<div class="dab-serch-result-cst">';
    $output .= '<table class="dab-serch-result-cst-tb" width="100%">';

    $output .= '<tbody><th></th><th>Rating</th><th>Name</th><th>Office</th><th>State</th></tbody>';

    if(!empty($divisionId)){
        $meta_query[] =  array(
            'key'     => 'dab_division',
            'value' =>$divisionId,
            'compare' => 'LIKE',
        );
    }
    if(!empty($dab_state)){
        $meta_query[] =  array(
            'key'     => 'dab_state',
            'value' =>$dab_state,
            'compare' => '=',
        );
    }
    if(!empty($dabname)){
        $meta_query[] =array(
            'key' => 'dab_name',
            'compare' => 'LIKE',
            'value' => $dabname
        );
    }
    if(!empty($zip)){
        $meta_query[] =array(
            'key' => 'dabzipcode',
            'compare' => '=',
            'value' => $zip
        );
    }
    $meta_query[] =  array(
        'key'     => 'dab_status',
        'value' =>'Approved',
        'compare' => '=',
    );

    $results_found = false;

    if(!empty($meta_query)){
        $args = array(
            'post_type'=>'voter-guide',
            'posts_per_page'   => -1,
            'order'            => 'DESC',
            'post_status'      => 'publish', 'relation' => 'AND',
            'meta_query' => array(  'relation' => 'OR', $meta_query),
        );

        $wp_query = get_posts( $args );

        foreach($wp_query as $result){
            $results_found = true;
            $name = get_post_meta( $result->ID, 'dab_name', true );
            $office = get_post_meta( $result->ID, 'dab_office', true );
            $dab_state = get_post_meta( $result->ID, 'dab_state', true );
            $dab_img = get_post_meta( $result->ID, 'dab_img', true );
            $banner_img = get_post_meta($result->ID,'post_banner_img',true);
            $open_link = "<a href='/voter-guide/$result->post_name'>";
            $close_link = "</a>";

            $image_url = wp_get_attachment_url($banner_img) ?: VOTER_GUIDE_PLUGIN_PATH_URL .'assets/img/dummy.png';;

            $output .= "<tr>";
            $output .= "<td> $open_link <img class='legislator-pic' src='" . $image_url . "'  alt='' /> $close_link </td>";
            $output .= "<td class='rating'>" . $open_link . get_post_meta( $result->ID, 'dab_rating', true ) . '%' . $close_link . "</h3></td>";
            $output .= "<td><h3 class='legislator'>" . $open_link . esc_html( $name) . $close_link . "</h3></td>";

            $output .= '<td>' . $open_link.  $office . $close_link . '</td>';

            $output .= '<td> ' . $open_link . $dab_state . $close_link . '</td>';
            $output .= "</tr>";

        }
    }

    $output .= '</table> ';

    if (!$results_found) {
        $output .= '<div class="no-result"> No result found</div>';
    }

    $output.'<div style="clear:both"></div></div>';
    echo  $output;
    wp_die();
}

function sing_page_dab($post_id){

    $post = get_post($post_id);
    $name = get_post_meta( $post_id, 'dab_name', true );
    $office = get_post_meta( $post_id, 'dab_office', true );
    $dab_state = get_post_meta( $post_id, 'dab_state', true );
    $dab_election = get_post_meta( $post_id, 'dab_election', true );
    $banner_img = get_post_meta($post_id,'post_banner_img',true);
    $output = '';
    $dab_img =wp_get_attachment_image_src($banner_img, 'full')  ;
    if(empty($banner_img)){ $iconURL= VOTER_GUIDE_PLUGIN_PATH_URL .'assets/img/dummy.png';  }else{ $iconURL=  $dab_img[0]; }
    $output .= "<div class='container'><div class='row'><div class='col-md-10 offset-md-1 py-5'>
<div class='dab_answer_page'>
<img src='/badge/{$post->post_name}' style='display:none;'>
   <table width='100%' cellpadding='10px' cellspacing='0' border='0'>
      <tr>
         <td width='35%'><img id='output-img' src='{$iconURL}' alt='{$name}'></td>
         <td width='65%'>
            <div>
                <div>
                <div><h2 id='name-ouput'>{$name}</h2></div>
                <div><p>State:</p></div>
                <div><p id='name-ouput'><strong>{$dab_state}</strong></p></div>
                <div><p>Office:</p></div>
                <div><p id='state-ouput'><strong>{$office}</strong></p></div>
                <div><p>Election:</p></div>
                <div><p id='state-ouput'><strong>{$dab_election}</strong></p></div>
                </div>
            </div>
         </td>
      </tr>";

    $quednary  = get_option('dab_field_option_name');
    $count = count( $quednary);

    if($count >0){
        $i = 1;
        $a =  $quednary;
        foreach( $a as $key=>$fields ){
            $a[$i]['field_value'] = get_field_value_dab($i);

            $i++;
        }
        $j= 1;
        foreach( $a as $fields ){
            if(array_key_exists("field_options",$fields)){
                $field_option =$fields['field_options'] ;
            }else{
                $field_option = ''; }
            $field_type = $fields['field_type'] ;
            $selected = $fields['field_value'];
            $field_name = $fields['field_name'];
            $field_position = $fields['field_position'];
            if (!empty($fields['field_value'])) {

                $newarry[]= array(
                    'field_type'=>$field_type ,
                    'field_option'=>  $field_option,
                    'value'=> $fields["field_value"],
                    'index'=> $j,
                    'field_name'=> $field_name,
                    'selected'=> $selected,
                    'field_position'=> $field_position
                );
                $j++;
            }
        }

        usort($newarry, function($a, $b) {
            $retval = $a['field_position'] <=> $b['field_position'];

            return $retval;
        });
        $output .= '<tr class="answer_dab row'.$j.'">';
        $output .= '<td colspan="2" >';

        $output .=  answer_page_dab($newarry)  ;
        $output .= '</td></tr>';

    }

    $output .=  '</table></div>';
    $output .= '</div></div></div> ';

    return $output;

}

function answer_page_dab($newarry){
    $checked ='';
    $output ='';
    foreach( $newarry as $array){
        $field_type = $array['field_type'];
        $field_option = $array['field_option'];
        $index = $array['index'];
        $value =$array['value'] ;
        $field_name = $array['field_name'];
        $selected = $array['selected'];
        //$field_position = $array['field_position'];
        if($field_type == 'text'){
            $output .= '<div class="quednary" style="display:flex;align-items: baseline;"> <p>'. $field_name.'</p> </div>';
            $output .=  "<div style='display:flex;'> <p class='dabans'> $value</p></div>";

        }else if($field_type == 'select'){
            $output .= '<div class="quednary" style="display:flex;align-items: baseline;"> <p>'. $field_name.'</p> </div>';
            $output  .=  "<div class='select_custa'>";
            $options = $field_option;
            $option  = explode(',',$options);

            $output .=  "<p class='dabans'>$selected</p>";

            $output .= "</div>";
        }else if($field_type == 'radio'){
            $options = $field_option;
            $option  = explode(',',$options);
            $s = 'checked';
            $output .= '<div class="quednary" style="display:flex;align-items: baseline;"> <p>'. $field_name.'</p> </div>';
            $output .= "<div class='dab-radio dab-redio'>  <p class='dabans'> {$value}</p></div>";

        }else if($field_type == 'checkbox'){

            $output .= '<div class="quednary" style="display:flex;align-items: baseline;"> <p>'. $field_name.'</p> </div>';
            $options = $field_option;
            $option  = explode(',',$options);
            $s = '';
            $output .= "<div class='dab-radio dab-checkbox'><ul clas='ans-dab'>";
            foreach($selected as $value){
                $s = 'checked';

                $output .= "<li>{$value}</li>";
            }
            $output .= "</ul></div>";
        }else if($field_type == 'textarea'){
            $output .= '<div class="quednary" style="display:flex;align-items: baseline;"> <p>'. $field_name.'</p> </div>';
            $output .=  "<p class='dabans textarea'> $value</p>";
        }
    }
    return $output;
}

function get_google_api($address, $zip, $state) {
    $key = get_option('new_option_name');
    $api_call = "https://www.googleapis.com/civicinfo/v2/representatives?key=$key&address=" . urlencode("$address, $state $zip");
    $request  = wp_remote_get( $api_call );
    $response = wp_remote_retrieve_body( $request );
    return json_decode($response);
}

add_action( 'wp_ajax_get_office', 'get_civic_office' );
add_action( 'wp_ajax_nopriv_get_office', 'get_civic_office' );
// get options for select list
function get_civic_office() {
    $state =  $_POST['state'];
    $post_id =  $_POST['posthidden'];
    $fulladd =  $_POST['dab_address'];
    $zip =  $_POST['zip'];
    $dab = get_google_api($fulladd, $zip, $state);

    $selected = get_post_meta( $post_id, 'dab_office', true );
    $output = '';

    $divisions = [];
    foreach($dab->divisions as $divisionId => $division) {
        $divisions[$divisionId] = $division;
    }
    ksort($divisions);
    foreach ($divisions as $divisionId => $division) {
        $output .=  "<optgroup label='$division->name'>";
        foreach ($division->officeIndices as $idx) {
            $office = $dab->offices[$idx];
            $s = ($selected == $office->name) ? 'selected' : '';
            $output .=  "<option value='{$office->name}'  division='{$divisionId}'  $s>" . $office->name . '</option>';
        }
        $output .= "</optgroup>";
    }

    echo $output;
    die();
}

// Auto fill Title and Slug for CPT's - 'companies', 'contacts', 'properties'
add_action( 'save_post', 'dab_save_post', 10,3 );
function dab_save_post( $post_id, $post, $update) {
    if ( !isset($_POST['save']) ) {
        return;
    }
    if ( 'trash' === $post->post_status ) {
        return;
    }
    if ( get_post_type( $post_id ) == 'voter-guide' ){

        $name = get_post_meta( $post_id, 'dab_name', true );
        $new_title = sanitize_title( $name );
        global $wpdb;
        $where = array( 'ID' => $post_id );

        $wpdb->update( $wpdb->posts, array( 'post_title' => $name,'post_name'=> $new_title, 'post_status'   =>  'publish'), $where );
    }
}

// approve status
add_action( 'wp_ajax_vote_approve_status', 'vote_approve_status' );
add_action( 'wp_ajax_nopriv_vote_approve_status', 'vote_approve_status' );
function vote_approve_status( ) {
    $value =  $_POST['approve'];
    $post_id =  $_POST['post_id'];
    update_post_meta( $post_id, 'dab_status',  $value) ;
    $status = get_post_meta( $post_id, 'dab_status', true ); 	 // get status
    // return status
    if($status == "Approved"){  $hold  ="Pending";}else{  $hold  ="Approved";}
    $return['status']= $value;
    $return['hold']= $hold;
    echo json_encode($return);
    die();

}

/* Bulk action  */
add_filter('bulk_actions-edit-voter-guide', function($bulk_actions) {
    $bulk_actions['voter-approve'] = __('Approve', 'txtdomain');
    $bulk_actions['voter-pending'] = __('Pending', 'txtdomain');
    return $bulk_actions;
});

add_filter('handle_bulk_actions-edit-voter-guide', function($redirect_url, $action, $post_ids) {
    if ($action == 'voter-approve') {
        foreach ($post_ids as $post_id) {
            update_post_meta( $post_id, 'dab_status',  'Approved') ;
        }
        $redirect_url = add_query_arg('voter-approve', count($post_ids), $redirect_url);
    }
    if ($action == 'voter-pending') {
        foreach ($post_ids as $post_id) {
            update_post_meta( $post_id, 'dab_status',  'Pending') ;
        }
        $redirect_url = add_query_arg('voter-pending', count($post_ids), $redirect_url);
    }
    return $redirect_url;
}, 10, 3);

function get_voter_excerpt($post_id) {
    global  $wpdb;
    $quednary  = get_option('dab_field_option_name');

    $count = count( $quednary);
    if($count >0){
        $i = 1;
        $a =  $quednary;
        foreach( $a as $key=>$fields ){
            $a[$i]['field_value'] = get_field_value_for_exverpt($i,$post_id);

            $i++;
        }
        foreach( $a as $fields ){
            $field_type = $fields['field_type'] ;
            if($field_type =='textarea' || $field_type = 'text' )
                $value = $fields['field_value'];
            $newarry[]= array(
                'field_type'=>$field_type ,
                'value'=> $value,

            );
        } }  $string = array();

    foreach($newarry as $datavalue){

        if(	$datavalue['field_type']  == 'textarea' ){
            $string[]=   $datavalue['value'];
        }
    }
    $textto_string = implode(",", $string);
    $trimmed_content = wp_trim_words($textto_string, 60, ' ');
    $where = array( 'ID' => $post_id );
    $wpdb->update( $wpdb->posts, array( 'post_excerpt' => $trimmed_content ), $where );

}

function get_field_value_for_exverpt($key,$post_id){
    $id = $post_id;
    $value = '';
    $data = get_post_meta($id, 'dab_fields_data', false );

    if(!empty($data)){
        foreach($data[0] as $keys => $values ){

            if($key == $keys){
                $value = $values['field_value'];
            }
        }
    }
    return $value;
}
