@extends('layouts.app')

@section('content')
    <div class="container">
        @include('layouts.flash_message')
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex">
                        <div class="list-unstyled mb-0 justify-content-center">
                            @if(Auth::user()->role != 'artist')
                                <a href="{{route('artists.index')}}" >
                                    <button class="btn btn-sm btn-danger" ><i class="link-icon" data-feather="arrow-left"></i> Back</button>
                                </a>
                            @endif

                            @if(auth()->user()->role != 'artist_manager')
                                <a href="{{ route('music.create', $artistDetail->id) }}">
                                    <button class="btn btn-sm btn-success">
                                        <i class="link-icon" data-feather="plus"></i> Add Song
                                    </button>
                                </a>
                            @endif

                        </div>
                        <div class="mx-auto">
                            <h4 class="text-primary mt-md-0">
                                Song Lists : {{ucfirst($artistDetail->first_name)}} {{ucfirst($artistDetail->last_name)}}

                            </h4>
                        </div>
                    </div>

                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Album Name</th>
                                <th>Title</th>
                                <th>Genre</th>
                                @if(auth()->user()->role != 'artist_manager')
                                    <th>Actions</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($songs as $key => $song)
                                <tr>
                                    <td>{{ $song->album_name }} </td>
                                    <td>{{ $song->title }}</td>
                                    <td>{{ $song->genre }}</td>
                                    @if(auth()->user()->role != 'artist_manager')
                                        <td>
                                            <ul class="d-flex list-unstyled mb-0 justify-content-center">
                                                <li class="me-2">
                                                    <a href="{{ route('music.edit', $song->id) }}" title="Edit Song">
                                                        <i class="link-icon" data-feather="edit"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#" class="delete"
                                                       data-href="{{ route('music.delete', $song->id) }}"
                                                       title="Delete Song">
                                                        <i class="link-icon" data-feather="delete"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center"><b>No Music records found!</b></td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    @include('song.common.scripts')
@endsection

