<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Resources\V2\BlogCollection;
use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Cache;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return Cache::remember('app.blogs', 86400, function () {
            return new BlogCollection(Blog::where('is_active', 1)->get());
        // });
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
    public function getBlog($slug)
    {
        $data=new \stdClass();

        $data->post=$this->formatBlog(Blog::where('slug',$slug)->first());

        $recentPosts = [];
        $featured=Blog::where('is_active', 1)->get();

        foreach($featured as $item){
            $recentPosts[]=$this->formatBlog($item);  
        }

        $data->recentPosts=$recentPosts;

        $data->blogCategoryList=BlogCategory::where('is_active', 1)->get();

        $data->relatedPosts=$recentPosts;

        return $data;
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

    public function formatBlog($blog)
    {
       $data= new \stdClass();

       $data->id= $blog->id;
       $data->author= "John Doe";
       $data->comments= 10;
       $data->content= $blog->description;
       $data->date= $blog->date;
       $data->slug= $blog->slug;
       $data->title= $blog->title;
       $data->type= $blog->type;
       $data->blog_categories= formatBlogCategories($blog->category, $blog->id);
       $data->video= $blog->video;
       $data->picture= formatBlogPictures($blog->banner, $blog->id);
       $data->small_picture= formatBlogPictures($blog->banner, $blog->id);

       return $data;

    }
}
