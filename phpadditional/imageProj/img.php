<?php
require 'vendor/autoload.php';

use Intervention\Image\ImageManager;

$manager = new ImageManager(['driver' => 'imagick']);
$dir = '/in/';
$f = scandir(getcwd() . $dir);
foreach ($f as $file) {
    if (($file !== '.') && ($file !== '..')) {
        $curPath = str_replace('/', '\\', getcwd() . $dir . $file);
        $image = $manager->make($curPath);

        $newPath = explode('.', str_replace('/', '\\', getcwd() . '/out/' . $file))[0];

        $image->save($newPath . '.webp');
    }
}