<?php
/* user form shortcode  */
ob_start();
function shortcode_dab_form(){
    $loader= VOTER_GUIDE_PLUGIN_PATH_URL .'assets/img/loader.gif';
    $iconURL= VOTER_GUIDE_PLUGIN_PATH_URL .'assets/img/dummy.png';
    $output = '';
    wpshout_save_post_if_submitted();
    $refresh = $_SERVER['PHP_SELF'];

    $output .= '<div class="main_form-dab" id="dab_main_form">
	<form action="" id="dab_form" action="'.$refresh.'" name="dab_form" method="POST" enctype="multipart/form-data" >
	
<table width="100%" cellpadding="10px" cellspacing="0" border="0" class="top-part">
        <tr>
        <td class="title">
        <label for="dab_name">Name</label>
        <input placeholder="Full Name" id="dab_name" name="dab_name" type="text" />
        </td>

        <td class="title">
        <label for="dab_name">Email</label>
        <input placeholder="Enter your email" id="dab_email" name="dab_email" type="email" required />
        </td>

         <td  class="title">
        <label for="dab_address">Street</label>
         <input placeholder="Street Address" value="" id="dab_address" name="dab_address" type="text" required />
         </td>
        <td class="title">
        <label for="dabzip_code">Zip</label>
        <input id="dabzip_code" name="dabzip_code" type="number" placeholder="Enter Zip code" maxlength="5"  />
        </td>
        <td class="title">
        <label for="dab_office">Office</label>
        <select name="dab_office" id="dab_office"> <option value=""  >Select Office</option> </select> <img id="searchdab" style="display:none;"src="'.$loader.'" width="30">
        </td>
        <td class="title photo-part">
        <div class="content-in">
        <img id="blah" src="'.$iconURL.'" alt="your image" />
        </div>
        <input class="field" id="choose_file" name="choose_file" type="file" onchange="readDABURL(this);" />
        <label for="choose_file">Select</label>
        </td>
        </tr>	
	
</table>
<table width="100%" cellpadding="10px" cellspacing="0" border="0" class="bottom-part extra-gap">
    <tr class="photo">
		<td >State</td>
		<td ><select name="dab_state" id="dab_state">';
    $output .= voter_guide_render_state_options();
    $output .= '
</select></td> 
	</tr> 

				<tr>
		<td>Election</td>
		<td>  <select name="dab_election" id="dab_election">
               <option value="2021">2021</option>
<option value="2022">2022</option>
<option value="2023">2023</option>
<option value="2024">2024</option>
<option value="2025">2025</option>
</select></td>
	</tr>';
    $output .=  wp_nonce_field('dab-frontend-post', 'dab-frontend-post', true, false);
    $quednary  = get_option('dab_field_option_name');

    $i = 1;
    foreach( $quednary as $fields ){
        if(array_key_exists("field_options",$fields)){
            $field_option =$fields['field_options'] ;
        }else{
            $field_option = ''; }
        $field_type = $fields['field_type'] ;

        if (!empty($fields['field_name'])) {
            $field_name = $fields['field_name'];
            $field_position = $fields['field_position'];

            $newarry[]= array(
                'field_type'=>$field_type ,
                'field_option'=>  $field_option,
                'index'=> $i,
                'field_name'=> $field_name,
                'field_position'=> $field_position,
            );

            $i++;
        }
    }

    /* filter the data with field_position */
    usort($newarry, function($a, $b) {
        $retval = $a['field_position'] <=> $b['field_position'];
        return $retval;
    });
    $output .=  create_front_fields($newarry);

    $output .= '<tr>	<td colspan="2" style="text-align: center;">
			  <input type="submit" name="submit" value="Submit">
		</td>
	</tr>
</table>
</form>
</div>
	';
    return $output ;
}
add_shortcode("voter_guide_form", "shortcode_dab_form");

function wpshout_save_post_if_submitted() {
    // Stop running function if form wasn't submitted
    if ( !isset($_POST['dab_name']) ) {
        return;
    }

    // Check that the nonce was set and valid
    if( !wp_verify_nonce($_POST['dab-frontend-post'], 'dab-frontend-post') ) {
        echo 'Did not save because your form seemed to be invalid. Sorry';
        return;
    }
    // Add the content of the form to $post as an array
    $post_new = array(
        'post_title'    => $_POST['dab_name'],
        'post_status'   => 'publish',   // Could be: publish
        'post_type' 	=> 'voter-guide' // Could be: `page` or your CPT
    );
    $post_id =  wp_insert_post($post_new);
    $data =  $_POST['fields'];
    update_post_meta( $post_id, 'dab_fields_data',  $_POST['fields']) ;
    update_post_meta( $post_id, 'dab_name', sanitize_text_field( $_POST['dab_name'] ) );

    update_post_meta( $post_id, 'dab_office', sanitize_text_field(  str_replace("'", "", $_POST['dab_office'])));
    update_post_meta( $post_id, 'dab_address', sanitize_text_field( $_POST['dab_address'] ) );

    update_post_meta( $post_id, 'dabzipcode', sanitize_text_field( $_POST['dabzip_code']));

    update_post_meta( $post_id, 'dab_email', sanitize_text_field( $_POST['dab_email'] ) );
    update_post_meta( $post_id, 'dab_state', sanitize_text_field( $_POST['dab_state'] ) );

    update_post_meta( $post_id, 'dab_election', sanitize_text_field( $_POST['dab_election'] ));
    if ( $_FILES ) {
        foreach ( $_FILES as $file => $array ) {

            $newupload = insert_attachment( $file, $post_id,$setthumb='false' );
            if(!empty($array['name'])){ update_post_meta( $post_id, 'post_banner_img', $newupload); set_post_thumbnail( $post_id, $newupload ); }
        }
    }
    update_post_meta( $post_id, 'dab_status', 'Pending');
    $pagename = basename(get_permalink());
    get_voter_excerpt($post_id);
    $redrct =  get_option('thankyou_dab_option');
    $r_url = get_permalink($redrct);
    wp_safe_redirect($r_url); exit();

}

function insert_attachment( $file_handler, $post_id, $setthumb='false' ) {

    if ( $_FILES[$file_handler]['error'] !== UPLOAD_ERR_OK ) {
        __return_false();
    }
    require_once( ABSPATH . "wp-admin" . '/includes/image.php' );
    require_once( ABSPATH . "wp-admin" . '/includes/file.php' );
    require_once( ABSPATH . "wp-admin" . '/includes/media.php' );
    $attach_id = media_handle_upload( $file_handler, $post_id );
    return $attach_id;
}

/* Show search form shortcode */
function shortcode_voter_guide_search_form(){
    $iconURL= VOTER_GUIDE_PLUGIN_PATH_URL .'assets/img/Photo.png';
    $output = '';

    $output .= '<div class="main_form-dab serach-dab"> <form id="dab_searchform">
<table width="100%" cellpadding="10px" class="rea bottom-part no-extra-gap"cellspacing="0" border="0">
    <tr>
        <td width="20%">Name</td>
        <td width="80%"><input  id="dabname" name="dabname" placeholder="Enter name" type="text"/></td>
    </tr>
        <tr>
        <td >State</td>
        <td ><select id="dabstate" name="dab_state" required>
		' . voter_guide_render_state_options() . '
</select></td>
    </tr>
                <tr>
        <td width="20%">Zip</td>
        <td width="80%"><input id="dabzip_code" name="dabzip_code" type="number" placeholder="Enter Zip code" maxlength="5"/> </td>
    </tr>    <tr>
        <td width="20%">Street.</td>
        <td width="80%"><input  id="dabfull_add" name="dabfull_add" type="text" placeholder="Enter Street Address" /> </td>
    </tr> 
           <tr>
                <td colspan="2" style="text-align: right;"> <div class="dabserch_button">
             <input type="submit" id="dab_search" value="Search"><img id="searchdab" style="display:none;"src="'.$iconURL.'" width="30"> </div>
        </td>
    </tr>   
</table>
</form> 
</div><div class="main_form-dab_res" id="dab_result_holder"></div>
	';
    return $output ;
}
add_shortcode("voter_guide_search_form", "shortcode_voter_guide_search_form");
