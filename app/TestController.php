<?php

namespace App;

class TestController
{
    public function test()
    {
        return view('json', 'Hola bro');
    }
}