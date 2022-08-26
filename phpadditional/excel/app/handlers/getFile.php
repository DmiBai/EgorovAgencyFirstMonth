<?php

require '../../vendor/autoload.php';
require '../classes/MySpreadsheet.php';

if (isset($_POST)) {
    if ((isset($_FILES['file']))) {
        $path = mb_substr(getcwd(), 0, strripos(getcwd(), '\\'));
        $path = mb_substr($path, 0, strripos($path, '\\'));

        move_uploaded_file($_FILES['file']['tmp_name'], $path . '\\public\\files\\' . 'drivers.xlsx');
        echo $path;


        $readFilePath = $_SERVER['DOCUMENT_ROOT'] . '/excel/public/files/drivers.xlsx';

        $spreadsheet = new MySpreadsheet($readFilePath);
        $spreadsheet->ReadDrivers();
        $spreadsheet->AddWaybills();

        $dir = $_SERVER['DOCUMENT_ROOT'] . '/excel/public/files/roadbills/';
        $f = scandir( $dir);
        $zip = new ZipArchive();
        $zip->open($_SERVER['DOCUMENT_ROOT'] . '/excel/public/files/' . "archive.zip",  ZipArchive::CREATE);
        foreach ($f as $file) {
            if (($file !== '.') && ($file !== '..')) {
                $zip->addFile($dir . $file);
            }
        }
        $zip->close();
    }
}