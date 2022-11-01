<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\City;

class StateController extends Controller
{
    public function fetchCities(Request $request)
    {
        // $data['cities'] = City::where("state_id",$request->state_id)->get(["name", "id"]);
        // return response()->json($data);
    }
}
