@extends('backend.layouts.app')

@section('content')

<div class="card">
      <div class="card-header">
          <h5 class="mb-0 h6">Doctor's Shedule</h5>
      </div>
      
      <div class="card-body">
      <form action="{{url('/admin/doctors/shedule/update/'.$available_date->id)}}" method="POST">
            @csrf

            <div class="form-group row">
                <label class="col-md-3 col-from-label">Doctor (D)</label>
                <div class="col-md-8">
                    <select name="doctor_id">
  
                           <option value="{{$available_date->doctor['id']}}">{{$available_date->doctor['name']}}</option>

                    </select>    
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-3 col-from-label">Date</label>
                <div class="col-md-8">
                    <input type="text" class="form-control" name="day" placeholder="dd-mm-yyyy">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-3 col-from-label">Time</label>
                <div class="col-md-8">
                    <select name="time">
                        @foreach($time_array as $time)
                        <option value="{{$time}}" @if($time==$available_date['time']) selected @endif>{{$time}}</option>
                        @endforeach
                    </select> 
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 col-from-label">Status</label>
                <div class="col-md-8">

                      <div class="form-check-inline">
                            <input class="form-check-input" name="is_active" type="radio" id="flexRadioDefault1" @if($available_date->is_active==1) checked @endif>
                            <label class="form-check-label" for="flexRadioDefault1">
                              Active
                            </label>
                      </div>
                      <div class="form-check-inline">
                            <input class="form-check-input" name="is_active" type="radio" id="flexRadioDefault2" @if($available_date->is_active==0) checked @endif>
                            <label class="form-check-label" for="flexRadioDefault2">
                              Inactive
                            </label>
                      </div>


                </div>
            </div>

            <div style="text-align:center">
            <button type="submit" name="button" class="btn btn-primary action-btn">Update</button>
            </div>

      </form>



      


    </div>      



</div>


@endsection

@section('script')

@endsection
