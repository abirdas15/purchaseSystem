@extends('admin.app')
@section('title')
    Dashbaord
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Dashboard /</span> User</h4>

            <!-- Basic Bootstrap Table -->
            <div class="card">
                <h5 class="card-header">User List</h5>
                <div class="table-responsive text-nowrap">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Phone Number</th>
                        </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!--/ Basic Bootstrap Table -->
        </div>
    </div>
@endsection
