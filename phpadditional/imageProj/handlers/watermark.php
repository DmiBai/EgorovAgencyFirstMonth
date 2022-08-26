<?php
require '../vendor/autoload.php';

use Intervention\Image\ImageManager;

$manager = new ImageManager(['driver' => 'imagick']);

if (isset($_FILES)) {

    $types = array('image/jpg', 'image/png', 'image/jpeg');
    if (in_array(strtolower($_FILES['file']['type']), $types)) {
        move_uploaded_file($_FILES['file']['tmp_name'], '../img/watermarked.png');

        $path = mb_substr(getcwd(), 0, strripos(getcwd(), '\\'));
        $image = $manager->make($path . '\img\watermarked.png');

        $width = $image->width();
        $height = $image->height();
        $size = 0;

        if ($height > $width) {
            $size = $width;
        } else {
            $size = $height;
        }

        $image->crop($size, $size);

        $watermark = $manager->make($path . '/img/egorov.png');
        $watermark->resize($size / 5, $size / 5);
        $image->insert($watermark, 'bottom-right');

        $image->save($path . '/img/watermarked.png');
        echo 'done';
    } else {
        echo 'incorrect format' . $_FILES['file']['type'];
    }
}

function lineCoor($angle, $x, $y, $length)
{
    $angle = deg2rad($angle);
    $resY = $length * sin($angle);
    $resY += $y;
    $resX = $length * cos($angle);
    $resX += $x;
    return array($resX, $resY);
}