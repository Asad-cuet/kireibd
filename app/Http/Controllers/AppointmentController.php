<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\AvailableDate;
use Illuminate\Support\Facades\File;
use DateTime;

class AppointmentController extends Controller
{
    public function __construct()
    {
        $this->time_array=[
            "09:00-09:30",
            "09:30-10:00",
            "10:00-10:30",
            "10:30-11:00",
            "11:00-11:30",
            "11:30-12:00",
            "12:00-12:30",
            "12:30-01:00",
            "01:00-01:30",
            "01:30-02:00",
            "02:00-02:30",
            "02:30-03:00",
            "03:00-03:30",
            "03:30-04:00",
            "04:00-04:30",
            "04:30-05:00",
            "05:00-05:30",
            "05:30-06:00",
            "06:00-06:30",
            "06:30-07:00",
            "07:00-07:30",
            "07:30-08:00",
            "08:00-08:30",
            "08:30-09:00",
            "09:00-09:30"
        ];
    }
    public function index()
    {
      //dd('dd');
      $doctors=Doctor::orderBy('id','desc')->get();
      $obj=[
        'doctors'=>$doctors
      ];
      return view('backend.appointment.doctors.index',$obj);
    }
    public function doctors_create()
    {
        return view('backend.appointment.doctors.create');
    }

    public function doctors_add(Request $request)
    {
        //dd($request->file('image'));

        //uploading image
        if($request->hasfile('image'))       
        {

             
             $file=$request->file('image');
             $extention=$file->getClientOriginalExtension();  
             if(array_search($extention,['example','jpg','png','jprg','gif']))
             {
                  $filename=uniqid().'.'.$extention;
                  $file->move('doctors/',$filename);  
                  if(!$file)
                  {
                      return "Error in image uploading";
                  }

             }
             else
             {
                  return back()->with('danger', $extention.' file is not allowed');
             }
             
        }


        $data=[
            'name'=>$request->input('name'),
            'image'=>$filename,
            'description'=>$request->input('description'),
            'created_at'=>time(),
            'updated_at'=>time()
        ];
        $doctor=Doctor::create($data);
        if($doctor)
        {
             return back()->with('status',"Doctor Added Successfully");
        }
        else
        {
             return back()->with('danger',"Error in Doctor Inserting");
        }

    }

    public function doctors_edit($doctor_id)
    {
        //dd($doctor_id);
        $doctor=Doctor::where('id',$doctor_id)->first();
        $obj=[
            'doctor'=>$doctor
          ];
        return view('backend.appointment.doctors.edit',$obj);
    }

    public function doctors_update(Request $request,$doctor_id)
    {
        //dd($doctor_id);


        if($request->hasfile('image'))       
        {

             
             $file=$request->file('image');
             $extention=$file->getClientOriginalExtension();  
             if(array_search($extention,['example','jpg','png','jprg','gif']))
             {
                  $filename=uniqid().'.'.$extention;
                  $file->move('doctors/',$filename);  
                  if($file)
                  {
                       $old=Doctor::where('id',$doctor_id)->first()->image;
                       $old_image='doctors/'.$old;
                       if(File::exists($old_image))
                       {
                            File::delete($old_image);
                       }
                       else
                       {
                          return back()->with('danger',"Error in old Image deleting");
                       }
                  }
                  else
                  {
                      return back()->with('danger',"Error in new Image uploading");
                  }

             }
             else
             {
                  return back()->with('status', $extention.' file is not allowed');
             }

             $data=[
                'name'=>$request->input('name'),
                'image'=>$filename,
                'description'=>$request->input('description'),
                'is_active'=>$request->input('is_active'),
                'updated_at'=>time()
             ];
        }
        else  //updating without image upload
        {
            $data=[
                'name'=>$request->input('name'),
                'description'=>$request->input('description'),
                'is_active'=>$request->input('is_active'),
                'updated_at'=>time()
             ];
        }

        $doctor=Doctor::where('id',$doctor_id)->update($data);
        if($doctor)
        {
            return redirect(route('doctors'))->with('status',"Doctor's Details Updated Successfully");
        }
        else
        {
            return back()->with('danger',"Doctor's Details Update Failed");
        }
    }




    public function doctors_delete($doctor_id)
    {
        if(Doctor::where('id',$doctor_id)->exists())
        {
            $doctor=Doctor::find($doctor_id);
            $old_image='doctors/'.$doctor->image;
            if(File::exists($old_image))
            {
                File::delete($old_image);
            }
            $doctor->delete();
            return redirect(route('doctors'))->with('status','Doctor Deleted Successfully');
        }
        else
        {
            return back()->with('danger',"This Id doesn't exist");
        }
    }



    ///Shedule

    public function doctors_shedule($doctor_id)
    {
            $available_dates=AvailableDate::where('doctor_id',$doctor_id)->orderBy('id','desc')->get();
            $doctors=Doctor::orderBy('id','desc')->get();

            $obj=[
                'available_dates'=>$available_dates,
                'doctors'=>$doctors,
                'time_array'=>$this->time_array
            ];
            return view('backend.appointment.doctors.shedule.create',$obj);
  

        
    }

    public function doctors_shedule_add(Request $request)
    {
        date_default_timezone_set("Asia/Dhaka");
        $current_date=date("Y-m-d h:i:sa");
        $day = strtotime($request->input('day'));
        $newformat = date('d-m-Y',$day);
        //dd($newformat);

        $data=[
             'doctor_id'=>$request->input('doctor_id'),
             'day'=>$newformat,
             'time'=>$request->input('time'),
             'created_at'=>$current_date,
             'updated_at'=>$current_date
        ];

        //dd($data);
        AvailableDate::create($data);

 

        return redirect(route('doctors'))->with('status',"Doctor's Shedule Added Successfully");

    }


    public function doctors_shedule_edit($available_date_id)
    {
        $available_date=AvailableDate::where('id',$available_date_id)->first();
        $obj=[
            'available_date'=>$available_date,
            'time_array'=>$this->time_array
        ];
        return view('backend.appointment.doctors.shedule.edit',$obj);
    }




    public function doctors_shedule_update(Request $request,$available_date_id)
    {
        date_default_timezone_set("Asia/Dhaka");
        $current_date=date("Y-m-d h:i:sa");

        $day = strtotime($request->input('day'));
        $data=[
             //'day'=>$newformat,
             'time'=>$request->input('time'),
             'is_active'=>$request->input('is_active'),
             'updated_at'=>$current_date
        ];

        //dd($data);
        $avl=AvailableDate::where('id',$available_date_id)->update($data);
        $doctor_id=AvailableDate::where('id',$available_date_id)->first()->doctor_id;
        return redirect('/admin/doctors/shedule/'.$doctor_id)->with('status',"Doctor's Shedule Updated Successfully");
    }






    public function doctors_shedule_delete($available_date_id)
    {
            $doctor_id=AvailableDate::where('id',$available_date_id)->first()->doctor_id;

            $available_date=AvailableDate::find($available_date_id);
            $available_date->delete();
            
            return redirect('/admin/doctors/shedule/'.$doctor_id)->with('status',"Doctor's Shedule Deleted Successfully");
    }
}
