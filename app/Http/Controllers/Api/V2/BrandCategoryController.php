<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Resources\V2\BrandCategoryCollection;
use App\Models\BrandCategory;
use Illuminate\Http\Request;

class BrandCategoryController extends Controller
{
    public function index()
    {

        return new BrandCategoryCollection(BrandCategory::all());
    }

}
