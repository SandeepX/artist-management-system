@extends('layouts.app')

@section('content')
    <div class="container">
        @include('layouts.flash_message')
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex">
                        <div class="list-unstyled mb-0 justify-content-center">
                            <a href="{{ route('users.create') }}">
                                <button class="btn btn-sm btn-success">
                                    <i class="link-icon" data-feather="plus"></i> Add User
                                </button>
                            </a>
                        </div>
                        <div class="mx-auto">
                            <h4 class="text-primary mt-md-0">Users Lists</h4>
                        </div>
                    </div>

                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Gender</th>
                                <th>Date of Birth</th>
                                <th>Address</th>
                                <th>Phone</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ \App\Models\User::ROLE[$user->role] }}</td>
                                    <td>{{ (\App\Models\User::GENDER[$user->gender])}}</td>
                                    <td>{{ $user->dob }}</td>
                                    <td>{{ $user->address }}</td>
                                    <td>{{ $user->phone }}</td>
                                    <td>
                                        <ul class="d-flex list-unstyled mb-0 justify-content-center">
                                            <li class="me-2">
                                                <a href="{{ route('users.edit', $user->id) }}" title="Edit User">
                                                    <i class="link-icon" data-feather="edit"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#" class="delete" data-href="{{ route('users.delete', $user->id) }}" title="Delete User">
                                                    <i class="link-icon" data-feather="delete"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center"><b>No Users records found!</b></td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="dataTables_paginate mt-3">
            {{ $users->appends($_GET)->links() }}
        </div>

    </div>

@endsection

@section('scripts')
    @include('users.common.scripts')
@endsection

