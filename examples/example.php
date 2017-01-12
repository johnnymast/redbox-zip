<?php
require 'autoload.php';
error_reporting(E_ALL);
ini_set('display_errors', true);

$instance = new \Redbox\Zip\Unzip(dirname(__FILE__).'/zip.zip');


echo 'done';