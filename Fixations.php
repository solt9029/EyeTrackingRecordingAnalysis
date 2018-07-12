<?php
require __DIR__ . "/vendor/autoload.php";
require_once './config.php';

use Solt9029\Utility;

$output_dir = './output/' . time();
mkdir($output_dir);

foreach (glob('./input/*.csv') as $file) {
  if (!strpos($file, 'eye')) {
    continue;
  }
  $pathinfo = pathinfo($file);
  $fixations = Utility::getFixations(DPI, PRECISION, DISTANCE, FLICK, MIN_DURATION, $file);
  $output_data = [];
  foreach ($fixations as $fixation) {
    $output_data[] = [$fixation['center_position'][0], $fixation['center_position'][1], $fixation['start_time'], $fixation['end_time'], $fixation['duration']];
  }

  $output_file = $output_dir . '/' . $pathinfo['basename'];
  touch($output_file);
  $file = fopen($output_file, 'w');
  if ($file) {
    foreach ($output_data as $line) {
      fputcsv($file, $line);
    }
  }
  fclose($file);
}