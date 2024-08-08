@extends('layouts.auth-layout')

@section('content')
<div class="container">
    @include('layouts.flash_message')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form id="" class="forms-sample"
                          action="{{route('custom-register')}}"
                          method="POST">
                        @csrf
                        @include('users.common.form', ['buttonName' => 'Register'])
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
