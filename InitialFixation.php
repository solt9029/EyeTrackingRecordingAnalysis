<?php
require __DIR__ . "/vendor/autoload.php";
require_once './config.php';

use Solt9029\Utility;
$utility = new Utility();

var_dump($utility->getInitialFixation(DPI, PRECISION, DISTANCE, FLICK, MIN_DURATION, './csv/test.csv'));