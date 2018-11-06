<?php

// files.phpで定義された、InitialFixation.php後のファイル群（InitialFixation.csv）に対して実行するよ！
// 例： php BundleInitialFixations.php

require_once './files.php';

$num = 16;

$data = [];

$fix = 13;

foreach ($files as $file) {
  $spl_file_object = new SplFileObject($file);
  $spl_file_object->setFlags(SplFileObject::READ_CSV);
  foreach ($spl_file_object as $line) {
    if (!is_null($line[0])) {
      $data[] = $line;
    }
  }
}

for ($i = 0; $i < count($data); ++$i) {
  $data[$i][] = ((float)$data[$i][3] - 512 - $fix + 26 * ($num / 4) * (int)$data[$i][0]) / 26;
  $data[$i][] = round($data[$i][4]);
}

$output = fopen("./output/bundle_fix_${num}.csv", 'w');
if ($output) {
  foreach ($data as $line) {
    fputcsv($output, $line);
  }
}
fclose($output);
