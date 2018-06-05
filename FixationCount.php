<?php
require __DIR__ . "/vendor/autoload.php";
use Solt9029\Utility;

$utility = new Utility();

define('DPI', 220.84); // 1インチ当たりのドット数
define('PRECISION', 0.5); // 計測誤差（度）
define('DISTANCE', 65); // 目からディスプレイまでの距離（cm）
define('FLICK', 0.01); // 固視微動（度）
define('MIN_DURATION', 100); // 注視したとする最低時間（ミリ秒） → いろいろな論があるっぽいので、とりあえず100ミリ秒で
// define('HZ', 90); // 視線計測機のサンプリング頻度 → CSVファイルに時刻が入っているのでいらない

$dpc = DPI / 2.54; // 1センチ当たりのドット数
$precision_error_range_cm = DISTANCE * tan(deg2rad(PRECISION)); // 計測誤差範囲（cm）
$flick_range_cm = DISTANCE * tan(deg2rad(FLICK)); // 固視微動範囲（cm）

$range_cm = $precision_error_range_cm + $flick_range_cm; // 計測誤差範囲+固視微動範囲（cm）
$range_px = $range_cm * $dpc;

$filename = './0_0_eye.csv';
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
    // 注視範囲を超えていた場合、今までのスタックを全て注視として、クリア
    if ($circle['radius'] > $range_px || $second_index + 1 >= count($recordings)) {
      array_pop($recording_stack); // 最後のやつははみ出てるってことなので削除しよう
      $duration = $recording_stack[count($recording_stack) - 1][2] - $recording_stack[0][2];
      if ($duration > MIN_DURATION) {
        $circle = $utility->getCircleContainingAllPositions($recording_stack);
        $fixations[] = [$circle['center_position'][0], $circle['center_position'][1], $recording_stack[0][2], $recording_stack[count($recording_stack) - 1][2], $duration];
        $first_index = $second_index;
      }
      break;
    }
  }
}

var_dump($fixations);