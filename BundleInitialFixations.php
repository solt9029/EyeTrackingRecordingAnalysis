<?php

require_once './files.php';

$num = 16;

$data = [];

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
  $data[$i][] = ((float)$data[$i][3] - 512 - 13 + 26 * ($num / 4) * (int)$data[$i][0]) / 26;
  $data[$i][] = round($data[$i][4]);
}

$output = fopen("./output/bundle_fix_${num}.csv", 'w');
if ($output) {
  foreach ($data as $line) {
    fputcsv($output, $line);
  }
}
fclose($output);
