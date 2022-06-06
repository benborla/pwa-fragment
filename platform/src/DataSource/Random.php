<?php

namespace App\DataSource;

class Random
{
    /**
     * Return random number
     *
     * @return int
     */
    public function get(int $min = 0, int $max = 0)
    {
        return rand($min, $max);
    }
}
