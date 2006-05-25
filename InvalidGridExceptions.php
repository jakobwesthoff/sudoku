<?php

class InvalidGridException extends Exception {
    public $row;
    public $col;
    public $block;
    public $number;
    
    public function __construct($row,$col,$block,$number) {
        $this->row = $row;
        $this->col = $col;
        $this->block  = $block;
        $this->number = $number;
    }
}

class InvalidGridRowException extends InvalidGridException {
    public function __construct($row,$col,$block,$number) {
        InvalidGridException::__construct($row,$col,$block,$number);
        $this->message = 'The grid that should be solved is invalid. The number "'.$this->number.'" exists more than one time on row "'.$this->row.'".';
    }
}

class InvalidGridColException extends InvalidGridException {
    public function __construct($row,$col,$block,$number) {
        InvalidGridException::__construct($row,$col,$block,$number);
        $this->message = 'The grid that should be solved is invalid. The number "'.$this->number.'" exists more than one time on column "'.$this->col.'".';
    }
}

class InvalidGridBlockException extends InvalidGridException {
    public function __construct($row,$col,$block,$number) {
        InvalidGridException::__construct($row,$col,$block,$number);
        $this->message = 'The grid that should be solved is invalid. The number "'.$this->number.'" exists more than one time in block "'.$this->block.'".';
    }
}

?>
