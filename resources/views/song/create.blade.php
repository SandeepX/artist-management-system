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
                            <a href="{{ Auth::user()->role == 'artist' ?
                                route('music.index') : route('music.get-artist-song', $artistId)}}" >
                                <button class="btn btn-sm btn-danger" ><i class="link-icon" data-feather="arrow-left"></i> Back</button>
                            </a>
                        </div>
                        <div class="mx-auto float-end">
                            <h4 class="text-primary mt-md-0 ">Song Add</h4>
                        </div>
                    </div>

                    <div class="card-body">
                        <form id="" class="forms-sample"
                              action="{{route('music.store')}}"
                              method="POST">
                            @csrf
                            @include('song.common.form', ['buttonName' => 'Create'])
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    @include('song.common.scripts')
@endsection


