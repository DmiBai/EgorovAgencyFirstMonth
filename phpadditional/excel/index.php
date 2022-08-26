<?php

require 'vendor/autoload.php';
require 'app/classes/MySpreadsheet.php';

$readFilePath = './public/files/drivers.xlsx';

$spreadsheet = new MySpreadsheet($readFilePath);
$spreadsheet->ReadDrivers();
$spreadsheet->AddWaybills();
