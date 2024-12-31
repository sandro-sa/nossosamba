<?php

namespace App\Exceptions\Music;

use Exception;

class MusicException extends Exception
{
    protected $message = "MusicException";
    public function render(){
        return response()->json([
            'message' => class_basename($this),
            'exception' => $this->getMessage()
        ],401);
    }
}