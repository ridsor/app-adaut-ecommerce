<?php

namespace App\Exceptions;

use Exception;

class ItemNotFoundException extends Exception
{
    protected $name;
    
    function __construct($name = "")
    {
         $this->name = $name;
    }

    public function render($request)
    {
        if ($request->user()->isAdmin()) {
            return response()->view('errors.admin.item-not-found', [
                'name' => $this->name
            ], 404);
        } else {
            return response()->view('errors.item-not-found', [
                'name' => $this->name
            ], 404);
        }
    }
}