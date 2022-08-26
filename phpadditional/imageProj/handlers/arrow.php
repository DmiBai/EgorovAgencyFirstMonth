<?php
require '../vendor/autoload.php';

use Intervention\Image\ImageManager;

$arrowAngle = 30;

$manager = new ImageManager(['driver' => 'imagick']);
$size = 500;

if (isset($_POST['angle'])) {
    $angle = $_POST['angle'];

    $path = mb_substr(getcwd(), 0, strripos(getcwd(), '\\'));
    $image = $manager->canvas($size, $size);

    $center = $size / 2;

    $coordinates = lineCoor($angle, $center, $center, $size / 3);
    $x = $coordinates[0];
    $y = $coordinates[1];

    $image->line($size / 2, $size / 2, $x, $y);

    $coordinates = lineCoor($angle + (90 - $arrowAngle + 90), $x, $y, $size / 6);
    $x1 = $coordinates[0];
    $y1 = $coordinates[1];
    $image->line($x, $y, $x1, $y1);

    $coordinates = lineCoor($angle - (90 - $arrowAngle + 90), $x, $y, $size / 6);
    $x2 = $coordinates[0];
    $y2 = $coordinates[1];
    $image->line($x, $y, $x2, $y2);

    $image->save($path . '/img/arrow.png');

    echo 'done';
} else {
    echo 'no';
}

function lineCoor($angle, $x, $y, $length)
{
    while(($angle >= 360) && ($angle < 0)) {
        if ($angle >= 360) {
            $angle -= 360;
        } else if ($angle < 0) {
            $angle += 360;
        }
    }
    $angle = deg2rad($angle);
    $resY = $length * sin($angle);
    $resY += $y;
    $resX = $length * cos($angle);
    $resX += $x;
    return array($resX, $resY);
}