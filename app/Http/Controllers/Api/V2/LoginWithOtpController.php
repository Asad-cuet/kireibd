<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginWithOtpController extends Controller
{
    public function sendOtp(Request $request)
    {
        return $request->all();
    }
}
