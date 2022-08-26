<?php
session_start();
if(isset($_POST['captcha'])){
    $capVal = $_SESSION['captcha'];
    if($_POST['captcha'] === $_SESSION['captcha']){
        echo json_encode(array('result' => 'success'));
    } else {
        echo json_encode(array('result' => 'failure'));
    }
}