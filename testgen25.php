<?php

include_once('sudoku.php');

ini_set('max_execution_time',0);

$s = new sudoku(25);

$startTime = microtime(true);
for($i=0; $i<10; $i++) {
    $s->create();
    echo 'generated: ', $i, "\n";
}
$endTime = microtime(true);

echo 'time elapsed: ', $endTime - $startTime;
?>
