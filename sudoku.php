<?php

class InvalidGridException extends Exception {}


class sudoku {
    
    private $gridsize;
    private $blocksize;
    private $row_numbers;
    private $col_numbers;
    private $block_numbers;
    public $grid;

    public function __construct($gridsize) {
        $this->gridsize = $gridsize;
        $this->blocksize = (int) sqrt($gridsize);
    }

    public function solve($grid) {
        $this->grid = $grid;
        
        $this->row_numbers = array();
        $this->col_numbers = array();
        $this->block_numbers = array();

        //fill the number associations with data
        for ($row=0; $row < $this->gridsize; $row++) {
            for ($col=0; $col < $this->gridsize; $col++) {
                if ($grid[$row][$col] === 0) {
                    continue;
                }
                if ($this->row_numbers[$row][$grid[$row][$col]] === true) {
                    throw new InvalidGridException('The grid that should be solved is invalid. The number "'.$grid[$row][$col].'" exists more than one time on row "'.$row.'".');
                }
                $this->row_numbers[$row][$grid[$row][$col]] = true;

                if ($this->col_numbers[$col][$grid[$row][$col]] === true) {
                    throw new InvalidGridException('The grid that should be solved is invalid. The number "'.$grid[$row][$col].'" exists more than one time on column "'.$col.'".');
                }
                $this->col_numbers[$col][$grid[$row][$col]] = true;
                
                $block = ((int)($col / $this->blocksize) + 1) + (((int)($row / $this->blocksize)) * $this->blocksize);
                if ($this->block_numbers[$block][$grid[$row][$col]] === true) {
                    throw new InvalidGridException('The grid that should be solved is invalid. The number "'.$grid[$row][$col].'" exists more than one time in block "'.$block.'".');
                }
                $this->block_numbers[$block][$grid[$row][$col]] = true;
            }
        }
        //the entered grid is valid

        //solve the grid using backtracking
        return $this->find_solution(0);
    }

    public function create() {
        //create a zero grid
        $this->grid = array();
        for ($i=0; $i<$this->gridsize; $i++) {
            for ($j=0; $j<$this->gridsize; $j++) {
                $this->grid[$i][$j] = 0;
            }
        }
        
        $this->row_numbers = array();
        $this->col_numbers = array();
        $this->block_numbers = array();

        $this->grid[mt_rand(0,$this->gridsize-1)][mt_rand(0,$this->gridsize-1)] = mt_rand(1,$this->gridsize);
        $this->find_solution(0,true);

        

    }

    private function find_solution($field,$random = false) {
        if ($field >= ($this->gridsize * $this->gridsize)) {
            //we have solved the grid
            return true;
        }
        
        $row = (int)($field / $this->gridsize);
        $col = $field - $row * $this->gridsize;
        $block = ((int)($col / $this->blocksize) + 1) + (((int)($row / $this->blocksize)) * $this->blocksize);
        $tested_random_numbers = array();

        if ($this->grid[$row][$col] === 0) {
            for ($i=1; $i<=$this->gridsize; $i++) {
                if ($random === true) {
                    do {
                        $randval = mt_rand(1, $this->gridsize);
                    } while (isset($tested_random_numbers[$randval]));
                    $tested_random_numbers[$randval] = true;
                    $number = $randval;
                } else {
                    $number = $i;
                }

                if ($this->row_numbers[$row][$number] !== true 
                    && $this->col_numbers[$col][$number] !== true 
                    && $this->block_numbers[$block][$number] !== true) {
                        $this->grid[$row][$col] = $number;
                        $this->row_numbers[$row][$number] = true;
                        $this->col_numbers[$col][$number] = true;
                        $this->block_numbers[$block][$number] = true;
                        //try the next field
                        if($this->find_solution($field+1,$random)) {
                            return true;
                        } else {
                            $this->grid[$row][$col] = 0;
                            unset($this->row_numbers[$row][$number]);
                            unset($this->col_numbers[$col][$number]);
                            unset($this->block_numbers[$block][$number]);
                        }
                }
            }
            //there is no possible solution
            //this should not happen
            return false;
        } else {
            return $this->find_solution($field+1,$random);
        }
    }
}

?>
