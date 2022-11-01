@extends('backend.layouts.app')

@section('content')
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>

                </tr>
            </thead>
            <tbody>
                @foreach($reviews as $key => $review)
                        <tr>

                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
        <div class="aiz-pagination">
           
        </div>
    </div>


@endsection

@section('script')

@endsection
