<?php

wp_enqueue_script('ajax', get_template_directory_uri() . '/scripts/ajax.js',
    array('jquery'), '1.0', true);

wp_localize_script('ajax', 'globalData', array('page' => 123));

require_once 'lib/func/create_rest_route.php';
//comment to commit wtf idk

