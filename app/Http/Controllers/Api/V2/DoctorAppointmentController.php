<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Resources\V2\BlogCollection;
use App\Http\Resources\V2\DoctorCollection;
use App\Models\Blog;
use App\Models\Doctor;
use App\Models\DoctorAppointment;
use Illuminate\Http\Request;
use Cache;

class DoctorAppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

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
        $row = new DoctorAppointment();
        // $row->customer_id = auth()->user()->id;
        $row->doctor_id = $request->doctor_id;
        $row->name = $request->name;
        $row->email = $request->email;
        $row->phone = $request->phone;
        $row->date = $request->date;
        $row->date = $request->time;
        $row->message = $request->message;
        $row->is_active = 0;
        $row->save();
        return response()->json([
            'result' => true,
            'message' => "Success"
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        return new BlogCollection(Blog::where('slug', $slug)->get());
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
