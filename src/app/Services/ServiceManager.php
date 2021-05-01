<?php


namespace App\Services;


use Illuminate\Http\Request;

interface ServiceManager
{
    public function getResponse(Request $request);
}
