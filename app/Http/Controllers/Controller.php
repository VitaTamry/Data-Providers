<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function jsonResponse($message = '', $data, $status = 200)
    {
        return response()->json(['message' => $message, 'payload' => $data], $status);
    }
}
