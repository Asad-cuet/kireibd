<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Available_date;
use Illuminate\Support\Facades\File;

class AppointmentController extends Controller
{
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
        if(!Available_date::where('doctor_id',$doctor_id)->exists())
        {
            $doctor=Doctor::where('id',$doctor_id)->first();
            $obj=[
                'doctor'=>$doctor
            ];
            return view('backend.appointment.doctors.shedule.create',$obj);
        }
        else
        {
            $available_date=Available_date::where('doctor_id',$doctor_id)->get();
            return view('backend.appointment.doctors.shedule.edit');
        }
        
    }

    public function doctors_shedule_add(Request $request,$doctor_id)
    {
        //dd($doctor_id);
        $days=[
            'saturday',
            'sunday',
            'monday',
            'tuesday',
            'wednesday',
            'thursday',
            'friday'
        ];
        date_default_timezone_set("Asia/Dhaka");
        $date=date("Y-m-d h:i:sa");
        foreach($days as $day)
        {
               //dd($day);
                $data=[
                    'doctor_id'=>$doctor_id,
                    'day'=>$day,
                    'time'=>$request->input($day),
                    'created_at'=>$date,
                    'updated_at'=>$date
                ];
                Available_date::create($data);

        }

        return redirect(route('doctors'))->with('status',"Doctor's Shedule Submitted Successfully");

    }
}
