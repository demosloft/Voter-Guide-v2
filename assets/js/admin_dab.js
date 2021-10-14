jQuery(function($) {

    $('body').on('click', '.wc_multi_upload_image_button', function(e) {
        e.preventDefault();

        var button = $(this),
            custom_uploader = wp.media({
                title: 'Insert image',
                button: { text: 'Use this image' },
                multiple: false
            }).on('select', function() {
                var attech_ids = '';
                attachments
                var attachments = custom_uploader.state().get('selection'),
                    attachment_ids = new Array(),
                    i = 0;
                attachments.each(function(attachment) {
                    attachment_ids[i] = attachment['id'];
                    attech_ids += ',' + attachment['id'];
                    if (attachment.attributes.type == 'image') {
                        $(button).siblings('ul').html('<li data-attechment-id="' + attachment['id'] + '"><a href="' + attachment.attributes.url + '" target="_blank"><img class="true_pre_image" src="' + attachment.attributes.url + '" /></a></li>');
                    }
                    i++;
                });

                var ids = $(button).siblings('.attechments-ids').attr('value');

                $(button).siblings('.attechments-ids').attr('value', attachment_ids);

                $(button).siblings('.wc_multi_remove_image_button').show();
            })
                .open();
    });

    $('body').on('click', '.wc_multi_remove_image_button', function() {
        $(this).hide().prev().val('').prev().addClass('button').html('Add Media');
        $(this).parent().find('ul').empty();
        return false;
    });
    /* Change */
    jQuery('select').on('change',function(){
        var row_id = jQuery(this).attr('data-tr');
        var id = jQuery(this).attr('data-id');

        var type = jQuery(this).val();
        if(type == 'select' || type == 'radio' || type == 'checkbox'   ){
            jQuery('.'+row_id+' td .dab_field').html('<input type="text" class="dabquestionnaire" name="fields['+id+'][field_options]">');
            //jQuery('.'+row_id+' td .dab_field').html('<input type="text" class="dabquestionnaire" name="fields[0][field_options]">');
        }else if(type == 'text' || type == 'textarea'){
            jQuery('.'+row_id+' td .dab_field').html('');
        }
    });

    jQuery(document).on('click', '.multi-upload-medias ul li i.delete-img', function() {
        var ids = [];

        ids.push(jQuery(this).attr('data-attechment-id'));

        jQuery('.multi-upload-medias').find('input[type="hidden"]').attr('value', ids);
    });
    /* order field validate */
    jQuery('.field_position').keyup(function () {
        this.value = this.value.replace(/[^0-9\.]/g,'');
    });

    $('.metabox_submit').click(function(e) {
        e.preventDefault();
        $('#publish').click();
    });
    $('#add-row').on('click', function() {
        var row = $('.empty-row.screen-reader-text').clone(true);
        row.removeClass('empty-row screen-reader-text');
        row.insertBefore('#repeatable-fieldset-one tbody>tr:last');
        var row_val = jQuery('#repeatable-fieldset-one').attr('data-questions');
        var sum_row = parseInt(row_val) + parseInt(1);

        var row_id = jQuery('#repeatable-fieldset-one  tr:last').attr('data-tr');
        var new_row = parseInt(row_id) + parseInt(1);
        var remove_row = row_id;
        jQuery('#repeatable-fieldset-one  tr:last').attr('data-tr', new_row);
        jQuery('tr.empty-row.screen-reader-text.que_row').removeClass('row-' + remove_row);

        jQuery('tr.empty-row.screen-reader-text.que_row').addClass('row-' + new_row);
        // jQuery('.row-' + new_row +' .dabquestionnaire').removeAttr("disabled");
        jQuery('.row-' + remove_row +' .dabquestionnaire').removeAttr("disabled");
        jQuery('.row-' + remove_row +' .select_type').removeAttr("disabled");
        jQuery('.row-' + remove_row +' .rwo_field').removeAttr("disabled");
        jQuery('.row-' + remove_row +' .field_position').removeAttr("disabled");
        jQuery('.row-' + new_row+' .dabquestionnaire').attr('id','field_name_'+new_row);
        jQuery('.row-' + new_row+' .select_type').attr('id','field_type_'+new_row);

        jQuery('.row-' + new_row+' .dabquestionnaire').attr('name','fields['+new_row+'][field_name]');
        jQuery('.row-' + new_row+' .select_type').attr('name','fields['+new_row+'][field_type]');
        jQuery('.row-' + new_row+' .select_type').attr('data-tr', 'row-'+new_row);
        jQuery('.row-' + new_row+' .select_type').attr('data-id', new_row);
        jQuery('.row-' + new_row+' .rwo_field').val('row-'+new_row);
        jQuery('.row-' + new_row+' .field_position').val(new_row);
        jQuery('.row-' + new_row+' .rwo_field').attr('name','fields['+new_row+'][field_row]');
        jQuery('.row-' + new_row+' .field_position').attr('name','fields['+new_row+'][field_position]');
        //jQuery('#repeatable-fieldset-one').attr('data-questions',new_row);

        jQuery('.row-' + new_row + ' p.dab-questions.next').text('Question:');

        return false;
    });
    $('.dab-remove-row').on('click', function() {
        $(this).parents('tr').remove();
        var row_val = jQuery('#repeatable-fieldset-one').attr('data-questions');
        jQuery('#repeatable-fieldset-one').attr('data-questions', parseInt(row_val) - parseInt(1));
        var new_val = jQuery('#repeatable-fieldset-one').attr('data-questions');
        if(new_val ==0){
            jQuery('#repeatable-fieldset-one tbody').prepend('	<tr data-row="" class="row-1" data-tr="1">		<td>		<p class="dab-questions">Question:</p>		<input type="text" class="dabquestionnaire" name="fields[1][field_name]" />		<select name="fields[1][field_type]" data-id="1" data-tr="row-1" class="select_type"> <option value="">Select field type</option><option value="text">Text </option>	<option value="radio">Radio </option>			<option value="checkbox">Checkbox </option>			<option value="textarea">Textarea </option>		</select>	<input type="hidden" class="rwo_field"   name="fields[1][field_row]"value="row-1" />		<input type="text" class="field_position dabquestionnaire"   name="fields[1][field_position]"value="1" />	<div class="dab_field"></div></td>	</tr>');
        }

        return false;
    });

    $('#repeatable-fieldset-one tbody').sortable({
        opacity: 0.6,
        revert: true,
        cursor: 'move',
        handle: '.sort'
    });
    var posthidden =jQuery('#posthidden').val();
    var state = jQuery('#dab_state option:selected').val();
    var zip =jQuery('#dabzip_code').val();
    jQuery('#searchdab').css('display','block');
    var dab_address =jQuery('#dab_address').val();
    var data = {
        action:'get_office',
        'zip':zip,  'dab_address':dab_address,
        'state':state,
        'posthidden':posthidden,
        dataType: 'JSON'
    };
    jQuery.post(ajaxurl, data, function(response) {
        jQuery("#dab_office").html(response.replace(/\"/g, ""));
        jQuery('#searchdab').css('display','none');
    });

    // Approve status
    jQuery('input[class="approvests"]').click(function(){
        var approve = jQuery(this).val();
        var  post_id =jQuery(this).attr('data-id');
        var  hold =jQuery(this).attr('hold-status');
        jQuery('#'+post_id+'_adsearchdab').css('display','block');
        var data = {
            action:'vote_approve_status',
            'approve':approve,
            'post_id':post_id,
            'hold':hold,
            dataType: 'JSON'
        };
        jQuery.post(ajaxurl, data, function(response) {
            var res = jQuery.parseJSON(response);
            jQuery('tr#post-'+post_id+' td.status.column-status').text(res.status);
            jQuery( '#'+post_id+'-approvests').val(res.hold);
            jQuery( '#'+post_id+'-approvests').attr('hold-status',res.hold);
            jQuery('#'+post_id+'_adsearchdab').css('display','none');
        });

    });
    /* Filter the Questionery */
    var $tbody = jQuery('.table_quednary');

    $tbody.find('tr').sort(function(a, b) {
        var tda = jQuery(a).attr('data-id'); // target order attribute
        var tdb = jQuery(b).attr('data-id'); // target order attribute
        // if a < b return 1
        return tda > tdb ? 1
            // else if a > b return -1
            : tda < tdb ? -1
                // else they are equal - return 0
                : 0;
    }).appendTo($tbody);
});

/* Remove row  */
jQuery(document).on('click', '.remove_dab', function() {
    var post_id = jQuery(this).attr('data-id');
    jQuery(this).removeClass('remove_dab').addClass('add_dab ');
    jQuery("i", this).removeClass('fas').addClass('far');
    /* Get selectd contact id*/
    var selected_id = jQuery(this).val();
    var data = {
        action:'remove_dab_action',
        'post_id':post_id,
        dataType: 'JSON'
    };
    jQuery.post(ajaxurl, data, function(response) {
        var res = jQuery.parseJSON(response);

    });
});

/* get_office */
jQuery(document).on('change', '.dab-voder-guid #dab_office', function(){
    var division = jQuery('option:selected', this).attr('division');
    jQuery('#dab-division').val(division);
});
jQuery(document).on('change keyup', '#dab_state, #dabzip_code, #dab_address', function(){
    jQuery('#dab-division').val('');
    var state = jQuery('#dab_state option:selected').val();
    var zip =jQuery('#dabzip_code').val();
    var dab_address =jQuery('#dab_address').val();
    jQuery('#searchdab').css('display','block');
    var data = {
        action:'get_office',
        'zip':zip,
        'dab_address':dab_address,
        'state':state,
        dataType: 'JSON'
    };
    jQuery.post(ajaxurl, data, function(response) {
        jQuery("#dab_office").html(response.replace(/\"/g, ""));
        jQuery('#searchdab').css('display','none');
    });

});

function rating_change_handler(val) {
    if (Number(val.value) > 100) {
        val.value = 100
    }
}
