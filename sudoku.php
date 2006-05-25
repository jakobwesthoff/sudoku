<?php

include_once('InvalidGridExceptions.php');

class sudoku {
    
    private $gridsize;
    private $blocksize;
    private $row_numbers_cache;
    private $col_numbers_cache;
    private $block_numbers_cache;
    public $grid;

    public function __construct($gridsize) {
        $this->gridsize = $gridsize;
        $this->blocksize = (int) sqrt($gridsize);
    }


    private function buildGridCache() {
        $this->row_numbers_cache = array();
        $this->col_numbers_cache = array();
        $this->block_numbers_cache = array();

        //fill the number associations with data
        for ($row=0; $row < $this->gridsize; $row++) {
            for ($col=0; $col < $this->gridsize; $col++) {
                if ($this->grid[$row][$col] === 0) {
                    continue;
                }
                $block = ((int)($col / $this->blocksize) + 1) + (((int)($row / $this->blocksize)) * $this->blocksize);
                
                if ($this->row_numbers_cache[$row][$this->grid[$row][$col]] === true) {
                    throw new InvalidGridRowException($row,$col,$block,$this->grid[$row][$col]);
                }
                $this->row_numbers_cache[$row][$this->grid[$row][$col]] = true;

                if ($this->col_numbers_cache[$col][$this->grid[$row][$col]] === true) {
                    throw new InvalidGridColException($row,$col,$block,$this->grid[$row][$col]);
                }
                $this->col_numbers_cache[$col][$this->grid[$row][$col]] = true;
                
                if ($this->block_numbers_cache[$block][$this->grid[$row][$col]] === true) {
                    throw new InvalidGridBlockException($row,$col,$block,$this->grid[$row][$col]);
                }
                $this->block_numbers_cache[$block][$this->grid[$row][$col]] = true;
            }
        }
        //the entered grid is valid

    }

    private function initGrid($grid = array()) {
        $this->grid = array();
        for ($i=0; $i<$this->gridsize; $i++) {
            for ($j=0; $j<$this->gridsize; $j++) {
                if (isset($grid[$i][$j])) {
                    $this->grid[$i][$j] = $grid[$i][$j];
                } else {
                    $this->grid[$i][$j] = 0;
                }
            }
        }
        $this->buildGridCache();
    }

    public function solve($grid) {
        $this->initGrid($grid);
        //solve the grid using backtracking
        return $this->find_solution(0);
    }

    public function create($difficulty = 46) {
        //generate a random number somewhere on the grid
        $grid = array();
        $grid[mt_rand(0,$this->gridsize-1)][mt_rand(0,$this->gridsize-1)] = mt_rand(1,$this->gridsize);
        $this->initGrid($grid);

        //solve the grid using random backtracking
        $this->find_solution(0,true);


        //save the complete grid for unique checking.
        $tryout_grid = $this->grid;

        //remove defined count of numbers in each block
        //loop through each block
        for ($i=0; $i<$this->gridsize; $i++) {
            $numbers = array();
            for($j=0; $j<((int)($difficulty/$this->gridsize)); $j++) {
                //get difficulty/blocksize different random numbers
                do {
                    $randval = mt_rand(0, $this->gridsize-1);
                } while (in_array($randval,$numbers));
                $numbers[] = $randval;
            }
//            var_dump($numbers);
            foreach($numbers as $number) {
                $row = (int)($i / $this->blocksize) * $this->blocksize + (int)($number / $this->blocksize);
                $col = (int) (($i % $this->blocksize) * $this->blocksize) + (int)($number % $this->blocksize);
        //        echo "Blocknumber: $i, Number in block: $number, Row: $row, Col: $col\n";
                $this->grid[$row][$col] = 0;
            }
        }

        if (!$this->find_solution(0,false,$tryout_grid)) {
            echo "unique grid.\n";
            return true;
        } else {
            echo "!! NOT a unique grid !!\n";
            return false;
        }
    }

    private function find_solution($field,$random = false, $tryout_grid = null) {
        if ($field >= ($this->gridsize * $this->gridsize)) {
            //we have solved the grid
            return true;
        }

        if ($tryout_grid === null) {
            for($i=0; $i<$this->gridsize; $i++) {
                for($j=0; $j<$this->gridsize; $j++) {
                    $tryout_grid[$i][$j] = 0;
                }
            }
        }
/*            echo "null\n";
        } else {
            echo "tryout_grid:\n";
            for ($j=0; $j<9; $j++) {
                echo implode(',',$tryout_grid[$j]),"\n";
            }
            echo "\n";
        }
*/        
        $row = (int)($field / $this->gridsize);
        $col = $field - $row * $this->gridsize;
        $block = ((int)($col / $this->blocksize) + 1) + (((int)($row / $this->blocksize)) * $this->blocksize);
        $tested_random_numbers = array_fill(0,$this->gridsize,false);

        if ($this->grid[$row][$col] === 0) {
            for ($i=1; $i<=$this->gridsize; $i++) {
                if ($random === true) {
                    do {
                        $randval = mt_rand(1, $this->gridsize);
                    } while ($tested_random_numbers[$randval] == true);
                    $tested_random_numbers[$randval] = true;
                    
                    $number = $randval;
                } else {
                    $number = $i;
                }

                if ($this->row_numbers_cache[$row][$number] !== true 
                    && $this->col_numbers_cache[$col][$number] !== true 
                    && $this->block_numbers_cache[$block][$number] !== true
                    && $tryout_grid[$row][$col] !== $number) {
                        $this->grid[$row][$col] = $number;
                        $this->row_numbers_cache[$row][$number] = true;
                        $this->col_numbers_cache[$col][$number] = true;
                        $this->block_numbers_cache[$block][$number] = true;
                        //try the next field
                        if($this->find_solution($field+1,$random,$tryout_grid)) {
                            return true;
                        } else {
                            //there is no possible solution in this path. Go back one step.
                            $this->grid[$row][$col] = 0;
                            unset($this->row_numbers_cache[$row][$number]);
                            unset($this->col_numbers_cache[$col][$number]);
                            unset($this->block_numbers_cache[$block][$number]);
                        }
                }
            }
            //there is no possible solution
            //this should only happen if tryout_grid is not equal to null. We have a unique grid then.
            return false;
        } else {
            return $this->find_solution($field+1,$random, $tryout_grid);
        }
    }
}

?>
