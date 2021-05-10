<?php

namespace App\Infraestructure\Database;

interface DatabaseManager
{
    public function set(String $field, String $value);
    public function get(String $field);
}
