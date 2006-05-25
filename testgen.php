<?php
include_once('sudoku.php');

ini_set('max_execution_time',0);

$testCount = 1;
$startTime = microtime(true);

//ob_start();
$s = new sudoku(9);
$u = 0;
for($i=0; $i<$testCount; $i++) {
    if ($s->create(81) === false) {
        $u++;
    }
    for ($j=0; $j<9; $j++) {
        echo implode(',',$s->grid[$j]),"\n";
    }
    echo "\n";
}

$endTime = microtime(true);
//ob_end_clean();

echo "Not unique puzzles: ", $u, "\n";
echo $endTime - $startTime,"\n";

echo $testCount / ($endTime - $startTime), " puzzels per second\n";

?>
