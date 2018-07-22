<?php
// Fixations.php後のファイル群に対して実行するよ！
// 例： php InitialFixation.php output/1532242523/20180717113505_tasuku/16

require __DIR__ . "/vendor/autoload.php";
require_once './config.php';

use Solt9029\Utility;

if (count($argv) < 2) {
  return;
}
$target_dir = $argv[1];
$filelist = Utility::getFileList($target_dir);
$output_data = [];

foreach ($filelist as $filename) {
  if (!strpos($filename, 'eye')) {
    continue;
  }

  $file = fopen($filename, 'r');
  $initial_fixation = fgetcsv($file);

  $pathinfo = pathinfo($filename);
  $exploded_filename = explode('_', $pathinfo['filename']);
  $output_data[] = [$exploded_filename[0], $exploded_filename[1], $initial_fixation[0], $initial_fixation[1]];
}

$output_file = $target_dir . '/' . 'InitialFixation.csv';
touch($output_file);
$file = fopen($output_file, 'w');
if ($file) {
  foreach ($output_data as $line) {
    fputcsv($file, $line);
  }
}
fclose($file);