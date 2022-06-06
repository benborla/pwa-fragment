<?php

namespace App\Service;

class Navigation
{
    public static function paths()
    {
        return [
            [
                'path' => '/',
                'name' => 'Home'
            ],
            [
                'path' => '/world',
                'name' => 'Hello'
            ],
            [
                'path' => '/en/translation',
                'name' => 'Translation'
            ],
            [
                'path' => '/data',
                'name' => 'Data'
            ],
            [
                'path' => '/color-shower',
                'name' => 'Color'
            ],
            [
                'path' => '/form-example',
                'name' => 'Form'
            ],
            [
                'path' => '/code-split',
                'name' => 'Code split'
            ],
            [
                'path' => '/eightball',
                'name' => 'Eightball'
            ],
        ];
    }
}
