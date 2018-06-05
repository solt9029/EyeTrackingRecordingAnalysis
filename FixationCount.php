<?php
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

$filename = './example.csv';
$file = fopen($filename, 'r');
$recordings = [];
while ($line = fgetcsv($file)) {
  $recordings[] = $line;
}



// $fixations = [];

// for ($i = 0; $i < count($recordings); $i++) {
//   $initial_recording = $recordings[$i];
//   while (true) {
//     if (dist($initial_recording[0], $initial_recording[1], $recordings[$i][0], $recordings[$i][1]) > $range_px) {
//       if ($recordings[$i - 1][2] - $initial_recording[2] > MIN_DURATION) {
//         $initial_recording[3] = $recordings[$i - 1][2] - $initial_recording[2];
//         $fixations[] = $initial_recording;
//       }
//       break;
//     }

//     // インクリメント
//     $i++;
//     if ($i >= count($recordings)) {
//       break;
//     }
//   }
// }

// var_dump($fixations);

// function dist($x1, $y1, $x2, $y2) {
//   return sqrt(pow(($x2 - $x1), 2) + pow(($y2 - $y1), 2));
// }

function dist($positions) {
  $x_dist = abs($positions[1][0] - $positions[0][0]);
  $y_dist = abs($positions[1][1] - $positions[0][1]);
  return sqrt(pow($x_dist, 2) + pow($y_dist, 2));
}