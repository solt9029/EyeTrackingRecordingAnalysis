<?php

// inputディレクトリに視線データを入れるよ！
/*
input/
  20181019154412_shiode/
  20181019155540_shiode/
  20181019160458_shiode/
*/ 
// 例： php Fixations.php

require __DIR__ . "/vendor/autoload.php";
require_once './config.php';

use Solt9029\Utility;

$input_dir = 'input';
$output_dir = 'output';
$filelist = Utility::getFileList($input_dir);
$time = time();

foreach ($filelist as $file) {
  if (!strpos($file, 'eye')) {
    continue;
  }

  $pathinfo = pathinfo($file);
  $output_file = $output_dir . '/' . $time . '/' . mb_substr($file, strlen($input_dir) + 1);
  if (!Utility::createFile($output_file)) {
    continue;
  }

  $fixations = Utility::getFixations(DPI, PRECISION, DISTANCE, FLICK, MIN_DURATION, $file);
  $output_data = [];
  foreach ($fixations as $fixation) {
    $output_data[] = [$fixation['center_position'][0], $fixation['center_position'][1], $fixation['start_time'], $fixation['end_time'], $fixation['duration']];
  }

  $file = fopen($output_file, 'w');
  if ($file) {
    foreach ($output_data as $line) {
      fputcsv($file, $line);
    }
  }
  fclose($file);
}