<?php
include_once('sudoku.php');

$s = new sudoku(9);

$sudoku_field[0] = array(0,2,0,9,0,7,4,0,6);
$sudoku_field[1] = array(7,8,0,0,0,5,0,0,9);
$sudoku_field[2] = array(0,0,0,0,0,0,8,3,0);
$sudoku_field[3] = array(3,0,0,2,0,0,0,0,0);
$sudoku_field[4] = array(0,1,6,0,4,0,0,2,3);
$sudoku_field[5] = array(0,4,0,0,5,0,0,0,1);
$sudoku_field[6] = array(4,0,1,6,0,2,0,7,0);
$sudoku_field[7] = array(8,9,0,0,0,1,0,0,0);
$sudoku_field[8] = array(0,6,0,5,3,0,0,0,0);

$s->solve($sudoku_field);

for ($i=0; $i<9; $i++) {
    echo implode(',', $s->grid[$i]), "\n";
}

$soution = array();
$solution[] = array(1,2,3,9,8,7,4,5,6);
$solution[] = array(7,8,4,3,6,5,2,1,9);
$solution[] = array(6,5,9,1,2,4,8,3,7);
$solution[] = array(3,7,8,2,1,6,9,4,5);
$solution[] = array(5,1,6,8,4,9,7,2,3);
$solution[] = array(9,4,2,7,5,3,6,8,1);
$solution[] = array(4,3,1,6,9,2,5,7,8);
$solution[] = array(8,9,5,4,7,1,3,6,2);
$solution[] = array(2,6,7,5,3,8,1,9,4);

$solved_correct = true;

for ($i=0; $i<9; $i++) {
    for ($j=0; $j<9; $j++) {
        if ($s->grid[$i][$j] !== $solution[$i][$j]) {
            $solved_correct = false;
            break 2;
        }
    }
}

echo ($solved_correct ? "Test passed." : "!!! Test failed !!!"), "\n";

?>
