<?php
require __DIR__ . "/vendor/autoload.php";
require_once './config.php';

use Solt9029\Utility;

$output_data = [];

foreach (glob('./csv/*.csv') as $file) {
  if (!strpos($file, 'eye')) {
    continue;
  }
  $pathinfo = pathinfo($file);
  $exploded_filename = explode('_', $pathinfo['filename']);
  $initial_fixation = Utility::getInitialFixation(DPI, PRECISION, DISTANCE, FLICK, MIN_DURATION, $file);
  $output_data[] = [$exploded_filename[0], $exploded_filename[1], $initial_fixation['center_position'][0], $initial_fixation['center_position'][1]];
}

$output_file = './output/' . time() . '.csv';
// touch($output_file);
$file = fopen($output_file, 'w');
if ($file) {
  foreach ($output_data as $line) {
    fputcsv($file, $line);
  }
}
fclose($file);