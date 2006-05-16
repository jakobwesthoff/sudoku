<?php
include_once('sudoku.php');

$s = new sudoku(9);
for($i=0; $i<5; $i++) {
    $s->create();
    for ($j=0; $j<9; $j++) {
        echo implode(',',$s->grid[$j]),"\n";
    }
    echo "\n";
}


?>
