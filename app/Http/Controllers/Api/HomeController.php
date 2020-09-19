<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function test(Request $request)
    {
        return $this->successResponse("test", 'sss', 200);
    }
}
