<?php

class Computer {
    public $cpu;
    public $ram;
    public $ssd;

    public function specs() {
        return "{$this->cpu}, {$this->ram} RAM, {$this->ssd} SSD";
    }
}

class ComputerBuilder {
    private $computer;

    public function __construct() {
        $this->computer = new Computer();
    }

    public function setCPU($cpu) { $this->computer->cpu = $cpu; return $this; }
    public function setRAM($ram) { $this->computer->ram = $ram; return $this; }
    public function setSSD($ssd) { $this->computer->ssd = $ssd; return $this; }

    public function build() { return $this->computer; }
}

$pc = (new ComputerBuilder())
        ->setCPU("Intel i7")
        ->setRAM("16GB")
        ->setSSD("512GB")
        ->build();

echo $pc->specs();
