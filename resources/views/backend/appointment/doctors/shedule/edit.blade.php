@extends('backend.layouts.app')

@section('content')

<div class="card">
      <div class="card-header">
          <h5 class="mb-0 h6">Doctor's Shedule</h5>
      </div>
      
      <div class="card-body">
      <form action="{{url('/admin/doctors/shedule/add/'.$doctor->id)}}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group row">
                <label class="col-md-3 col-from-label">Saturday</label>
                <div class="col-md-8">
                    <input type="text" class="form-control" name="saturday" placeholder="Write Time Range">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-3 col-from-label">Sunday</label>
                <div class="col-md-8">
                    <input type="text" class="form-control" name="sunday" placeholder="Write Time Range">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-3 col-from-label">Monday</label>
                <div class="col-md-8">
                    <input type="text" class="form-control" name="monday" placeholder="Write Time Range">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-3 col-from-label">Tuesday</label>
                <div class="col-md-8">
                    <input type="text" class="form-control" name="tuesday" placeholder="Write Time Range">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-3 col-from-label">Wednesday</label>
                <div class="col-md-8">
                    <input type="text" class="form-control" name="wednesday" placeholder="Write Time Range">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-3 col-from-label">Thursday</label>
                <div class="col-md-8">
                    <input type="text" class="form-control" name="thursday" placeholder="Write Time Range">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-3 col-from-label">Friday</label>
                <div class="col-md-8">
                    <input type="text" class="form-control" name="friday" placeholder="Write Time Range">
                </div>
            </div>

            <div style="text-align:center">
            <button type="submit" name="button" class="btn btn-primary action-btn">Save</button>
            </div>

      </form>
      </div>      



</div>


@endsection

@section('script')

@endsection
