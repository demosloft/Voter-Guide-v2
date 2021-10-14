jQuery(document).ready(function () {

    jQuery('#dab_form').validate({ // initialize the plugin
        rules: {
            'dab_name': {
                required: true,
            },'dab_state': {
                required: true,
            },'dab_office': {
                required: true,
            }
        },
        messages: {
            'dab_name': {
                required: "Please enter your name",
            },
            'dab_state': {
                required: "Please select state",
            },
            'dab_office': {
                required: "Please enter your office name",
            }
        },
        submitHandler: function (form) { // for demo
            //  alert('valid form submitted'); // for demo
            return true; // for demo
        }
    });

    jQuery('#dab_searchform').validate({
        rules: {
            'dab_name': {
                required: true,
            },'dab_state': {
                required: true,
            },'dab_office': {
                required: true,
            }
        },
        messages: {

            'dab_state': {
                required: "Please select state",
            }
        },
        submitHandler: function (form, event) {
            event.preventDefault();
            if (jQuery('#dabstate').val().length  != ''){
                var shortstate =jQuery('#dabstate').val();
                var zip =jQuery('#dabzip_code').val();
                var dabname =jQuery('#dabname').val();
                var dabfull_add =jQuery('#dabfull_add').val();
                var return_zip = getState(zip) ;
                if (zip.length  != ''){
                    if(return_zip != shortstate ){
                        jQuery('.dab_error.error').text('Invalid Zipcode.'); return false;
                    }  }
                jQuery('#searchdab').css('display','block');
                var data = {
                    action:'dab_get_api_data',
                    'dabname':dabname,
                    'dabfull_add':dabfull_add,
                    'zip':zip,
                    'state':shortstate,
                    dataType: 'JSON'
                };
                jQuery.post(ajaxurl, data, function(response) {
                    jQuery('#searchdab').css('display','none');
                    if (response != "Error1" && response != "Error2") {
                        jQuery("#dab_result_holder").html( response.replace(/\"/g, "") );
                    } else {
                        jQuery("#dab_result_holder").html( "Address or zip code not found.  Please try again.." );
                    }
                });
            }
        }
    });

});
