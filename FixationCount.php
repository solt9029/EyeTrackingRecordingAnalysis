<?php
require __DIR__ . "/vendor/autoload.php";
require_once './config.php';

use Solt9029\Utility;
$utility = new Utility();

$dpc = DPI / 2.54; // 1センチ当たりのドット数
$precision_error_range_cm = DISTANCE * tan(deg2rad(PRECISION)); // 計測誤差範囲（cm）
$flick_range_cm = DISTANCE * tan(deg2rad(FLICK)); // 固視微動範囲（cm）
$range_cm = $precision_error_range_cm + $flick_range_cm; // 計測誤差範囲+固視微動範囲（cm）
$range_px = $range_cm * $dpc;

$filename = './csv/test.csv';
$file = fopen($filename, 'r');
$recordings = [];
while ($line = fgetcsv($file)) {
  // 同時刻のものは1つだけ格納する
  if (count($recordings) > 0) {
    if ($recordings[count($recordings) - 1][2] === $line[2]) {
      continue;
    }
  }
  $recordings[] = $line;
}

$fixations = [];

for ($first_index = 0; $first_index < count($recordings); $first_index++) {
  $recording_stack = [];
  for ($second_index = $first_index; $second_index < count($recordings); $second_index++) {
    $recording_stack[] = $recordings[$second_index];
    $circle = $utility->getCircleContainingAllPositions($recording_stack);

    // 範囲外になった場合
    if ($circle['radius'] > $range_px) {
      array_pop($recording_stack);
      $duration = $recording_stack[count($recording_stack) - 1][2] - $recording_stack[0][2];
      if ($duration > MIN_DURATION) {
        $circle = $utility->getCircleContainingAllPositions($recording_stack);
        $fixations[] = [
          'center_position' => $circle['center_position'],
          'start_time' => $recording_stack[0][2],
          'end_time' => $recording_stack[count($recording_stack) - 1][2],
          'duration' => $duration
        ];
        $first_index = $second_index - 1;
      }
      break;
    }

    // 最終行の記録の場合
    if ($second_index + 1 >= count($recordings)) {
      $duration = $recording_stack[count($recording_stack) - 1][2] - $recording_stack[0][2];
      if ($duration > MIN_DURATION) {
        $fixations[] = [
          'center_position' => $circle['center_position'],
          'start_time' => $recording_stack[0][2],
          'end_time' => $recording_stack[count($recording_stack) - 1][2],
          'duration' => $duration
        ];
        $first_index = $second_index;
      }
      break;
    }
  }
}

$fixation_count = [
  'count' => count($fixations),
  'fixations' => $fixations
];

var_dump($fixation_count);