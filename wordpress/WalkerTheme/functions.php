<?php

require_once 'vendor/autoload.php';

require_once 'lib/content/menu.php';

//create custom post_type
require_once 'lib/act/add-cust-ptype-service.php';
add_action( 'init', 'create_post_type' );

//create taxonomy
require_once 'lib/act/add-tax-locations.php';
add_action( 'init', 'add_taxonomy_location', 0 );
