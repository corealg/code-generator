@extends('layouts.app')

@section("page_title") User @endsection

@section("content_header") User @endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create User</div>

                <div class="card-body">
                    {!! Form::open(['url' => 'user/save']) !!}
                        @include('users.form')
                            
                        <button type="submit" class="btn btn-primary mr-1 mb-1"><i class="bx bxs-save"></i> Save</button>
                        @can('list', \App\User::class)
                            <a href="{{url('users')}}" class="btn btn-light-secondary mr-1 mb-1">
                                <i class="bx bx-left-arrow-alt"></i> Back to list
                            </a>
                        @else
                            <a href="#" class="btn btn-light-secondary mr-1 mb-1 disabled">
                                <i class="bx bx-left-arrow-alt"></i> Back to list
                            </a>
                        @endcan
                        {!!validationHintsMessage()!!}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection