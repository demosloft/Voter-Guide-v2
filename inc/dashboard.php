<h1> DAB Dashboard</h1>
<div style="padding-left: 0px; padding-right:20px; padding-top:0px;" class="dab_dashboard">

    <form method="post" action="options.php">
        <?php settings_fields( 'dab-plugin-settings-group' ); ?>
        <?php do_settings_sections( 'dab-plugin-settings-group' ); ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">Google Civic Key:</th>
                <td>
                    <input type="password" name="new_option_name" value="<?php echo esc_attr( get_option('new_option_name') ); ?>" />
                    <p>Get your API Key at the <a href="https://console.developers.google.com/apis/credentials" target="_blank">Google APIs</a></p>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row">Email: </th>
                <td><input type="email" name="some_other_option" value="<?php echo esc_attr( get_option('some_other_option') ); ?>" /></td>
            </tr>
            <tr valign="top">
                <th scope="row">Term & condition link: </th>
                <td><input type="url" name="term_dab_option" value="<?php echo esc_attr( get_option('term_dab_option') ); ?>" /></td>
            </tr>
            <tr>
                <th scope="row">Thank you page url: </th>
                <td>
                    <select name="thankyou_dab_option">
                        <?php $selected = get_option('thankyou_dab_option');
                        if( $pages = get_pages() ){
                            foreach( $pages as $page ){
                                $s = ($selected == $page->ID)?'selected':'';
                                echo '<option value="' . $page->ID .'" '.$s.' >' . $page->post_title . '</option>';
                            }
                        }
                        ?>
                    </select>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><label>Shortcodes:</label></th>
                <td colspan="2">
                    <table>
                        <tbody>
                        <tr>
                            <th>Search: </th>
                            <td><input type="text" name="tmp1" value="[voter_guide_search_form]" style="border:none; background:transparent; box-shadow:none; width: 300px;"></td>
                        </tr>
                        <tr>
                            <th> User Form: </th>
                            <td><input type="text" name="tmp1" value="[voter_guide_form]" style="border:none; background:transparent; box-shadow:none; width: 300px;"></td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>

        </table>

        <?php submit_button(); ?>

    </form>

    <div class="clear"></div>
</div>
