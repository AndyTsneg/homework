<?php

namespace Login\Controllers;

use View;

class Base
{
    public function __construct()
    {
        session_start();
    }
}
