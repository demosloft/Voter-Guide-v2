<?php

function voter_guide_social_media_preview_handler($candidate_slug) {
    $background_image = ABSPATH . '/wp-content/uploads/2021/09/social-media-banner-rating-background.png';
}

function voter_guide_badge_handler($candidate_slug) {
    $query = new WP_Query(array(
        'post_type' => VOTER_GUIDE_SLUG,
        'post_status' => 'publish',
        'post_name__in' => [$candidate_slug],
    ));


    if (!$query->have_posts()) {
        echo 'not found';
        exit;
    }

    $query->the_post();
    $post_id = get_the_ID();
    $rating = get_post_meta( $post_id, 'dab_rating', true );
    voter_guide_badge_processor($rating);
}

function voter_guide_social_media_preview_processor($score) {

}

function voter_guide_badge_processor($score) {
    $text = $score . '%';

    // these fields should be editable in the main plugin settings
    $source_image_path = ABSPATH . '/wp-content/uploads/2021/09/legalize-happiness-badge.png';
    $size = 80;
    $angle = 0;
    $quality = 8;
    $x = 250;
    $y = 200;

    $font_filename = VOTER_GUIDE_PLUGIN_PATH . 'assets/Open_Sans/OpenSans-ExtraBold.ttf';
    $centered = voter_guide_center($size, $angle, $font_filename, $text, $x, $y);

    $final_image_path = ABSPATH . 'wp-content/cache/voter-guide-badge/' . $score . '.png';
    if (voter_guide_read_image($final_image_path)) {
        exit;
    }
    // if the image already existed, the above will exit
    $image = voter_guide_load_image($source_image_path);

    if ($score >= 90) {
        $color = imagecolorallocate($image, 0, 255, 0);
    } elseif ($score >= 80) {
        $color = imagecolorallocate($image, 200, 255, 0);
    } elseif ($score >= 50) {
        $color = imagecolorallocate($image, 255, 100, 0);
    } else {
        $color = imagecolorallocate($image, 255, 0, 0);
    }

    imagettftext($image, $size, $angle, $centered[0], $centered[1], $color, $font_filename, $text);
    voter_guide_save_image($image, $final_image_path, $quality);
    // read the image again now that it exists
    voter_guide_read_image($final_image_path);
    exit;
}

function voter_guide_load_image($image_path) {
    $image = imagecreatefrompng($image_path);
    imagesavealpha($image, TRUE);
    return $image;
}

function voter_guide_save_image($image, $image_path, $quality) {
    if (!file_exists(dirname($image_path))) {
        mkdir(dirname($image_path), 755, true);
    }
    imagepng($image, $image_path, $quality);
}

function voter_guide_read_image($image_path) {
    if (file_exists($image_path)) {
        status_header( 200 );
        header('Content-type: image/png');
        $date = new DateTime();
        $date->modify("+7 day");
        $date->setTimezone(new DateTimeZone('GMT'));
        header('Expires: ' . $date->format('D, d M Y H:i:s e'));
        readfile($image_path);
        return true;
    } else {
        return false;
    }
}

function voter_guide_center($size, $angle, $font_filename, $string, $x, $y) {
    $box = imageftbbox($size, $angle, $font_filename, $string);
    $width = $box[4] - $box[6];
    // this is distance from the baseline, so top is more negative than the bottom
    $height = $box[7] - $box[1];
    $newXY = [
        // x
        $x - ($width/2),
        // y needs te be offset by the baseline, the numbers are not from zero
        $y - ($height/2) - $box[1]
    ];
    return $newXY;
}
