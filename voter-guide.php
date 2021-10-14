<?php
/**
 * Plugin Name: Voter Guide
 * Plugin URI:
 * Description: Voter Guide is a free WordPress plugin giving your site visitor an easy way to find United States US senators and representatives.
 * Version: 1.0
 * Author: Loft
 * Author URI:
 **/
if ( !defined('ABSPATH') ) exit();
define('VOTER_GUIDE_PLUGIN_PATH_URL', plugin_dir_url(__FILE__));
define('VOTER_GUIDE_PLUGIN_PATH', plugin_dir_path(__FILE__));
defined('VOTER_GUIDE_SLUG') or define('VOTER_GUIDE_SLUG', 'voter-guide');
register_activation_hook( __FILE__, 'dab_install' );
require_once(ABSPATH . 'wp-admin/includes/file.php');
define( 'CUSTOM_METABOXES_DIR', VOTER_GUIDE_PLUGIN_PATH_URL .'/admin/metaboxes' );
require_once('inc/admin_function.php');
function dab_install()
{
    update_option('dab_cache', 1);
    update_option('dab_cache_time', 30);
    update_option('dab_themes', 'modern');
    update_option('dab_photos_last_modified', '1307992245');
    update_option('dab_options', array(0=>'title', 1=>'first_name', 2=>'last_name', 3=>'picture', 4=>'chamber', 5=>'state_rank', 6=> 'state_name', 7=> 'website', 8=> 'contact_form'));
    if ( ! current_user_can( 'activate_plugins' ) ) return;

    global $wpdb;

    if ( null === $wpdb->get_row( "SELECT post_name FROM {$wpdb->prefix}posts WHERE post_name = 'voter-thank-you'", 'ARRAY_A' ) ) {

        $current_user = wp_get_current_user();

        // create post object
        $page = array(
            'post_title'  => __( 'Thank you' ),
            'post_name' => 'voter-thank-you',
            'post_status' => 'publish',
            'post_author' => $current_user->ID,
            'post_type'   => 'page',
            'post_content' => '<div id="dabsucess"> Your form successfully! submitted :) </div>',
        );

        // insert the post into the database
        $id =   wp_insert_post( $page );
        update_option('thankyou_dab_option',$id);
    }
}
add_action( 'admin_menu','register_my_custom_menu_page');
function register_my_custom_menu_page() {

    $iconURL= VOTER_GUIDE_PLUGIN_PATH_URL .'assets/img/dashicon.png';
    add_menu_page('Voter guide', 'Voter guide', 'manage_options', 'dab-productions','dashboard_page',$iconURL);
    add_submenu_page('dab-productions', 'Questions', 'Questions', 'manage_options', 'dab-settings','setting_page');
    add_submenu_page('dab-productions', 'Submissions', 'Submissions', 'manage_options', 'edit.php?post_type='.VOTER_GUIDE_SLUG);
}

function dashboard_page(){
    // include page
    require_once(VOTER_GUIDE_PLUGIN_PATH.'inc/dashboard.php');
}
function setting_page(){
    // include page
    require_once(VOTER_GUIDE_PLUGIN_PATH.'inc/setting_page.php');
}
add_action('init', 'dab_init');
function dab_init(){
    // include page
    require_once(VOTER_GUIDE_PLUGIN_PATH.'inc/shortcode/form-shortcode.php');
    require_once(VOTER_GUIDE_PLUGIN_PATH.'inc/templates/single-questionnaire.php');
}

add_action( 'admin_enqueue_scripts', 'load_admin_styles' );
function load_admin_styles() {
    wp_enqueue_style( 'admin_css_dab', VOTER_GUIDE_PLUGIN_PATH_URL . 'assets/css/admin_style.css', false, '1.0.0' );
    wp_enqueue_script( 'admin_jsdab', VOTER_GUIDE_PLUGIN_PATH_URL . 'assets/js/admin_dab.js', false, '1.0.0' );
}

add_action( 'wp_enqueue_scripts', 'load_dab_styles' );
function load_dab_styles() {
    wp_enqueue_script('jquery');
    wp_enqueue_style( 'main_css_dab', VOTER_GUIDE_PLUGIN_PATH_URL . 'assets/css/dab_style.css', false, '1.0.0' );
    wp_enqueue_script( 'main_jsdab', VOTER_GUIDE_PLUGIN_PATH_URL . 'assets/js/dab_script.js', false, '1.0.0' );
    ?>
    <script type="text/javascript">
        var ajaxurl = <?php echo json_encode( admin_url( "admin-ajax.php" ) ); ?>;
        var security = <?php echo json_encode( wp_create_nonce( "dab-special-string" ) ); ?>;
    </script>
    <?php
}

add_action( 'wp_footer', 'dab_footer_scripts_styles' );
function dab_footer_scripts_styles() {
    wp_enqueue_script( 'validate_jsdab', VOTER_GUIDE_PLUGIN_PATH_URL . 'assets/js/jquery.validate.js');
    wp_enqueue_script( 'custom_jsdab', VOTER_GUIDE_PLUGIN_PATH_URL . 'assets/js/custom.js', false, '1.0.0' );
}

add_action( 'admin_init', 'register_dab_plugin_settings' );
function register_dab_plugin_settings() {
    //register our settings
    register_setting( 'dab-plugin-settings-group', 'new_option_name' );
    register_setting( 'dab-plugin-settings-group', 'some_other_option' );
    register_setting( 'dab-plugin-settings-group', 'option_etc' );
    register_setting( 'dab-plugin-settings-group', 'thankyou_dab_option' );
    register_setting( 'dab-plugin-settings-group', 'term_dab_option' );
//register our settings
    register_setting( 'dab-form-question-group', 'field_option_name' );
}
add_action('init', 'register_questionnaire_post_types');
function register_questionnaire_post_types()
{
    $name = $singular_name = 'Submissions';

    if (post_type_exists(VOTER_GUIDE_SLUG)) {
        return;
    }
    $fat_event_setting = get_option('voter-guide_setting');

    register_post_type(VOTER_GUIDE_SLUG,
        array(
            'label' => esc_html__('Submissions', 'voter-guide'),
            'description' => esc_html__('Questionnaire Description', 'voter-guide'),
            'labels' => array(
                'name' => $name,
                'singular_name' => $singular_name,
                'menu_name' => ucfirst($name),
                'parent_item_colon' => esc_html__('Parent Item:', 'voter-guide'),
                'all_items' => sprintf(esc_html__('All %s', 'voter-guide'), $name),
                'view_item' => esc_html__('View Questionnaire', 'voter-guide'),
                'add_new_item' => sprintf(esc_html__('Add New  %s', 'voter-guide'), $name),
                'add_new' => esc_html__('Add Questionnaire', 'voter-guide'),
                'edit_item' => esc_html__('Edit ', 'voter-guide'),
                'update_item' => esc_html__('Update Questionnaire', 'voter-guide'),
                'search_items' => esc_html__('Search Questionnaire', 'voter-guide'),
                'not_found' => esc_html__('Not found', 'voter-guide'),
                'not_found_in_trash' => esc_html__('Not found in Trash', 'voter-guide'),
            ),
            'supports' => array('title', 'thumbnail'),
            'public' => true,
            'show_ui' => true,
            '_builtin' => false,
            'has_archive' => true,
            'show_in_menu' => 'edit.php?page=voter-guide',
            'exclude_from_search' => true,
            'menu_icon' => 'dashicons-calendar-alt',
            'hierarchical' => true,
            'rewrite' => array('slug' => VOTER_GUIDE_SLUG, 'with_front' => true),
        )
    );

    $flush_rewrite = get_option('dab_flush_rewrite',0);
    if($flush_rewrite!=1){
        flush_rewrite_rules();
        update_option('dab_flush_rewrite',1);
    }
}

add_filter( 'single_template', 'dab_custom_post_type_template' );
function dab_custom_post_type_template($single_template) {
    global $post;

    if ($post->post_type == 'voter-guide' ) {
        $single_template = VOTER_GUIDE_PLUGIN_PATH.'inc/templates/single-questionnaire.php';
    }
    return $single_template;

}

add_action( 'parse_request', function ( $wp ) {
    if ( preg_match('/^badge/', $wp->request) ) {
        require_once plugin_dir_path(__FILE__) . 'inc/image_processor.php';
        voter_guide_badge_handler($wp->query_vars['attachment']);
        exit;
    } elseif (preg_match('/^voter-guide-og/', $wp->request)) {
        require_once plugin_dir_path(__FILE__) . 'inc/image_processor.php';
        voter_guide_social_media_preview_handler($wp->query_vars['attachment']);
        exit;
    }
} );

function voter_guide_render_state_options($selected = '') {
    $state_opt = Array("AL","AK","AZ","AR","CA","CO","CT","DE","DC","FL","GA","HI","ID","IL","IN","IA","KS","KY","LA","ME","MD","MA","MI","MN","MS","MO","MT","NE","NV","NH","NJ","NM","NY","NC","ND","OH","OK","OR","PA","RI","SC","SD","TN","TX","UT","VT","VA","WA","WV","WI","WY");

    $state = array("Alabama", "Alaska", "Arizona", "Arkansas", "California", "Colorado", "Connecticut", "Delaware", "District of Columbia", "Florida", "Georgia", "Hawaii", "Idaho", "Illinois", "Indiana", "Iowa", "Kansas", "Kentucky", "Louisiana", "Maine", "Maryland", "Massachusetts", "Michigan", "Minnesota", "Mississippi", "Missouri", "Montana", "Nebraska", "Nevada", "New Hampshire", "New Jersey", "New Mexico", "New York", "North Carolina", "North Dakota", "Ohio", "Oklahoma", "Oregon", "Pennsylvania", "Rhode Island", "South Carolina", "South Dakota", "Tennessee", "Texas", "Utah", "Vermont", "Virginia", "Washington", "West Virginia", "Wisconsin", "Wyoming");
    $count = count($state);

    $output = "<option value='' selected>Select A State</option>";
    for($i=1; $i<$count; $i++){
        $s = ($state_opt[$i] == $selected)?'selected':'';
        $output .= "<option value='$state_opt[$i]' data='$state[$i]' $s >$state[$i]</option>";
    }

    return $output;
}
