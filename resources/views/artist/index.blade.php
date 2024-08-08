@extends('layouts.app')

@section('content')
    @include('layouts.flash_message')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <a href="{{ route('artists.create') }}">
                                <button class="btn btn-sm btn-success me-2">
                                    <i class="link-icon" data-feather="plus"></i> Add Artist
                                </button>
                            </a>


                        </div>

                        <div class="text-center flex-grow-1">
                            <h4 class="text-primary mb-0">Artist Lists</h4>
                        </div>

                        <div>

                            <a href="{{ route('artists.export') }}">
                                <button class="btn btn-sm btn-primary me-2">
                                    <i class="link-icon" data-feather="download"></i> Export
                                </button>
                            </a>

                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#importModal">
                                <i class="link-icon" data-feather="upload"></i> Import
                            </button>
                        </div>
                    </div>


                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Gender</th>
                                <th>Date of Birth</th>
                                <th>Address</th>
                                <th>First Release Year</th>
                                <th>No of Album Released</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($artists as $artist)
                                <tr>
                                    <td>{{ $artist->first_name }} {{ $artist->last_name }}</td>
                                    <td>{{ (\App\Models\User::GENDER[$artist->gender])}}</td>
                                    <td>{{ $artist->dob }}</td>
                                    <td>{{ $artist->address }}</td>
                                    <td>{{ $artist->first_release_year ?? '-' }}</td>
                                    <td>{{ $artist->no_of_albums_released }}</td>
                                    <td>
                                        <ul class="d-flex list-unstyled mb-0 justify-content-center">
                                            <li class="me-2">
                                                <a href="{{ route('music.get-artist-song', $artist->user_id) }}" title="View Artist Album Records">
                                                    <i class="link-icon" data-feather="music"></i>
                                                </a>
                                            </li>
                                            <li class="me-2">
                                                <a href="{{ route('artists.edit', $artist->user_id) }}" title="Edit User">
                                                    <i class="link-icon" data-feather="edit"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#" class="delete" data-href="{{ route('artists.delete', $artist->user_id) }}" title="Delete Artist">
                                                    <i class="link-icon" data-feather="delete"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center"><b>No Artist records found!</b></td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="dataTables_paginate mt-3">
            {{ $artists->appends($_GET)->links() }}
        </div>

        <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="importModalLabel">Import Artists</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="csv_sample" class="form-label">Download Sample CSV Format</label>
                            <a href="{{ asset('updatedcsv.csv') }}" class="btn btn-xs btn-secondary" download>Download</a>
                        </div>
                        <form action="{{ route('artists.import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="csv_file" class="form-label">Choose CSV File</label>
                                <input type="file" name="csv_file" class="form-control" id="csv_file" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Import CSV</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    @include('artist.common.scripts')
    <script>
        feather.replace();
    </script>
@endsection

