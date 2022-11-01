@extends('backend.layouts.app')

@section('content')

<div class="card">
      <div class="card-header">
          <h5 class="mb-0 h6">Add Doctor</h5>
      </div>
      
      <div class="card-body">
      <form action="{{route('doctors.add')}}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group row">
                <label class="col-md-3 col-from-label">Doctor Name <span class="text-danger">*</span></label>
                <div class="col-md-8">
                    <input type="text" class="form-control" name="name" placeholder="Doctor Name" required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-3 col-from-label">Description</label>
                <div class="col-md-8">
                    <textarea type="text" class="form-control" name="description" placeholder="Write Description..."></textarea>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-3 col-from-label">Image</label>
                <div class="col-md-8">
                    <input type="file" class="form-control" name="image" placeholder="Product Name">
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
