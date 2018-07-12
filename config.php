<?php
define('DPI', 96.42); // 1インチ当たりのドット数Tobii T60
// define('DPI', 143.66); // 1インチ当たりのドット数iiyama
// define('DPI', 220.84); // 1インチ当たりのドット数lavie

define('PRECISION', 0.5); // 計測誤差（度）
define('DISTANCE', 65); // 目からディスプレイまでの距離（cm）
define('FLICK', 0.01); // 固視微動（度）
define('MIN_DURATION', 100); // 注視したとする最低時間（ミリ秒） → いろいろな論があるっぽいので、とりあえず100ミリ秒で
// define('HZ', 90); // 視線計測機のサンプリング頻度 → CSVファイルに時刻が入っているのでいらない