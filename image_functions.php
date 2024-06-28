<?php
function resizeImage($source_path, $destination_path, $max_width = 600, $max_height = 400) {
    list($source_width, $source_height, $source_type) = getimagesize($source_path);

    switch ($source_type) {
        case IMAGETYPE_GIF:
            $source_gdim = imagecreatefromgif($source_path);
            break;
        case IMAGETYPE_JPEG:
            $source_gdim = imagecreatefromjpeg($source_path);
            break;
        case IMAGETYPE_PNG:
            $source_gdim = imagecreatefrompng($source_path);
            break;
    }

    $source_aspect_ratio = $source_width / $source_height;
    $desired_aspect_ratio = $max_width / $max_height;

    if ($source_aspect_ratio > $desired_aspect_ratio) {
        $temp_height = $max_height;
        $temp_width = (int) ($max_height * $source_aspect_ratio);
    } else {
        $temp_width = $max_width;
        $temp_height = (int) ($max_width / $source_aspect_ratio);
    }

    $temp_gdim = imagecreatetruecolor($temp_width, $temp_height);
    imagecopyresampled(
        $temp_gdim,
        $source_gdim,
        0, 0,
        0, 0,
        $temp_width, $temp_height,
        $source_width, $source_height
    );

    $x0 = ($temp_width - $max_width) / 2;
    $y0 = ($temp_height - $max_height) / 2;

    $desired_gdim = imagecreatetruecolor($max_width, $max_height);
    imagecopy(
        $desired_gdim,
        $temp_gdim,
        0, 0,
        $x0, $y0,
        $max_width, $max_height
    );

    switch ($source_type) {
        case IMAGETYPE_GIF:
            imagegif($desired_gdim, $destination_path);
            break;
        case IMAGETYPE_JPEG:
            imagejpeg($desired_gdim, $destination_path, 80);
            break;
        case IMAGETYPE_PNG:
            imagepng($desired_gdim, $destination_path, 7);
            break;
    }

    imagedestroy($source_gdim);
    imagedestroy($temp_gdim);
    imagedestroy($desired_gdim);
}
