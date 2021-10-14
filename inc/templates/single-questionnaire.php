<?php
if(is_singular( )){

    get_header();
    $post_id = get_the_ID();
    echo sing_page_dab($post_id);
    get_footer();
}
