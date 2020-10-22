<?php

namespace App;

class Product
{
    private $t_shirt;
    private $jacket;
    private $pants;
    private $shoes;
    private $taxes;

    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    public function __set($property, $value) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
    }

}
