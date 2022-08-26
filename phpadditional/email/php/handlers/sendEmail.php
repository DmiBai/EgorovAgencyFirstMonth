<?php

require '../classes/EmailWorker.php';
require '../lib/functions.php';

if ((isset($_POST['username'])) && (isset($_POST['email'])) && (isset($_POST['message']))) {
    $username = $_POST['username'];
    $emailWorker = $_POST['email'];
    $message = $_POST['message'];

    $emailWorker = new EmailWorker($username, $emailWorker, $message);
    $emailWorker->connectDatabase('mod4');

    $path = mb_substr(getcwd(), 0, strripos(getcwd(), '\\'));
    $path = mb_substr($path, 0, strripos($path, '\\'));

    if ((isset($_FILES['file']))) {
        foreach ($_FILES as $file) {
            $originalName = $file['file']['name'];

            $timestamp = timestampToStr($emailWorker->getTimestamp());

            $fileName = str_replace(' ', '_', $timestamp . '_' . $_FILES['file']['name']);

            $pathToFile = str_replace('/', '\\', $path . '/public/files/' . $fileName);

            move_uploaded_file($file, $pathToFile);

            $emailWorker->addFile($fileName);
        }
    }

    $emailWorker->sendEmail(true, true);
} else {
    echo 'warn: ';
}

