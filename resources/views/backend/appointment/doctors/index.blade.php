@extends('backend.layouts.app')

@section('content')
<div class="card">

    <div class="card-header">
         <h3>All Doctors</h3>
    </div>

    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                   <th>ID</th>
                   <th>Image</th>
                   <th>Name</th>
                   <th>status</th>
                   <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($doctors as $doctor)
                        <tr>
                            <td>{{$doctor['id']}}</td>
                            <td><img src="{{asset('doctors/'.$doctor->image)}}" style="height:70px;width:auto" alt="Doctor Image"></td>
                            <td>{{$doctor['name']}}</td>
                            <td>
                                @if($doctor['is_active']==1)
                                     <div class="">Active</div>
                                @else
                                     <div class="text-danger">Inactive</div>
                                @endif
                            </td>
                            <td>
                                  <a href="{{url('/admin/doctors/edit/'.$doctor['id'])}}" class="btn-sm btn-secondary">Edit</a>
                                  <a href="{{url('/admin/doctors/shedule/'.$doctor['id'])}}" class="btn-sm btn-primary">Shedule</a>
                                  <a href="{{url('/admin/doctors/delete/'.$doctor['id'])}}" class="btn-sm btn-danger" onclick="return confirm('Click Ok to Delete')">Delete</a>
                            </td>
                        </tr>
                @endforeach
            </tbody>
        </table>
        <div class="aiz-pagination">
           
        </div>
    </div>
</div>    


@endsection

@section('script')

@endsection
