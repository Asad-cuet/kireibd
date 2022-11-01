@extends('backend.layouts.app')

@section('content')

<div class="card">
      <div class="card-header">
          <h5 class="mb-0 h6">Add Doctor</h5>
      </div>
      
      <div class="card-body">
      <form action="{{url('/admin/doctors/update/'.$doctor->id)}}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group row">
                <label class="col-md-3 col-from-label">Doctor Name <span class="text-danger">*</span></label>
                <div class="col-md-8">
                    <input type="text" class="form-control" value="{{$doctor->name}}" name="name" placeholder="Doctor Name" required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-3 col-from-label">Description</label>
                <div class="col-md-8">
                    <textarea type="text" class="form-control" name="description" placeholder="Write Description...">{{$doctor->description}}</textarea>
                </div>
            </div>


            <div class="form-group row">
                <label class="col-md-3 col-from-label">Image</label>
                <div class="col-md-8">
                  <div class="mt-2 mb-2">
                        <img src="{{asset('doctors/'.$doctor->image)}}" style="height:70px;width:auto" alt="">
                  </div>
                    <input type="file" class="form-control" name="image" placeholder="Product Name">
                </div>
            </div>

            <div class="form-group row">
                  <label class="col-md-3 col-from-label">Status</label>
                  <div class="col-md-8">

                        <div class="form-check-inline">
                              <input class="form-check-input" name="is_active" type="radio" id="flexRadioDefault1" @if($doctor->is_active==1) checked @endif>
                              <label class="form-check-label" for="flexRadioDefault1">
                                Active
                              </label>
                        </div>
                        <div class="form-check-inline">
                              <input class="form-check-input" name="is_active" type="radio" id="flexRadioDefault2" @if($doctor->is_active==0) checked @endif>
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
