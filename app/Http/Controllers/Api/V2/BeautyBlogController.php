<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Resources\V2\BeautyBlogCollection;
use App\Http\Resources\V2\KireiYoutubeCollection;
use App\Models\BeautyBlog;
use App\Models\BeautyBlogCategory;
use App\Models\KireiYoutube;
use Illuminate\Http\Request;
use Cache;
class BeautyBlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Cache::remember('app.beauty_blog', 86400, function () {
            $data=[];
            $categories= BeautyBlogCategory::all();
            foreach ($categories as $category) {
                $category->books= new BeautyBlogCollection(BeautyBlog::where('category_id', $category->id)->get());
                $data[]=$category;
            }
            return $data;
        });
    }
    public function feature()
    {
        return Cache::remember('app.beauty_blog', 86400, function () {
            return new BeautyBlogCollection(BeautyBlog::latest()->take(3)->get());
        });
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
            return new BeautyBlogCollection(BeautyBlog::where('slug', $id)->get());
      
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
