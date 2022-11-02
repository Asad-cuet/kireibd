@extends('backend.layouts.app')

@section('content')

<div class="card">
      <div class="card-header">
          <h5 class="mb-0 h6">Doctor's Shedule</h5>
      </div>
      
      <div class="card-body">
      <form action="{{url('/admin/doctors/shedule/add')}}" method="POST">
            @csrf

            <div class="form-group row">
                <label class="col-md-3 col-from-label">Doctor (D)</label>
                <div class="col-md-8">
                    <select name="doctor_id">
                        @foreach($doctors as $doctor)
                           <option value="{{$doctor['id']}}">{{$doctor['name']}}</option>
                        @endforeach
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
                        <option value="{{$time}}">{{$time}}</option>
                        @endforeach
                    </select> 
                </div>
            </div>

            <div style="text-align:center">
            <button type="submit" name="button" class="btn btn-primary action-btn">Save</button>
            </div>

      </form>


<div style="text-align:center">


      <table class="table aiz-table mb-0">
        <thead>
            <tr>
               <th>ID</th>
               <th>Name</th>
               <th>Date</th>
               <th>Time</th>
               <th>Status</th>
               <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($available_dates as $avl_date)
                    <tr>
                        <td>{{$avl_date['id']}}</td>
                        <td>{{$avl_date->doctor['name']}}</td>
                        <td>{{$avl_date['day']}}</td>
                        <td>{{$avl_date['time']}}</td>
                        <td>
                            @if($avl_date['is_active']==1)
                                 <div class="">Active</div>
                            @else
                                 <div class="text-danger">Inactive</div>
                            @endif
                        </td>
                        <td>
                              <a href="{{url('/admin/doctors/shedule/edit/'.$avl_date['id'])}}" class="btn-sm btn-secondary">Edit</a>
                              <a href="{{url('/admin/doctors/shedule/delete/'.$avl_date['id'])}}" class="btn-sm btn-danger" onclick="return confirm('Click Ok to Delete')">Delete</a>
                        </td>
                    </tr>
            @endforeach
        </tbody>
    </table>

</div>







      </div>      



</div>


@endsection

@section('script')

@endsection
