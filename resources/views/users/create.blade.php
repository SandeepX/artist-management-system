@extends('layouts.app')
@section('title','User Add')
@section('content')
    <section class="content">
        <div class="container">
            @include('layouts.flash_message')
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex">
                        <div class="list-unstyled mb-0 justify-content-center">
                            <a href="{{route('users.index')}}" >
                                <button class="btn btn-sm btn-danger" ><i class="link-icon" data-feather="arrow-left"></i> Back</button>
                            </a>
                        </div>
                        <div class="mx-auto float-end">
                            <h4 class="text-primary mt-md-0 ">User Add</h4>
                        </div>
                    </div>

                    <div class="card-body">
                        <form id="" class="forms-sample"
                              action="{{route('users.store')}}"
                              method="POST">
                            @csrf
                            @include('users.common.form', ['buttonName' => 'Create'])
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    @include('users.common.scripts')
@endsection


