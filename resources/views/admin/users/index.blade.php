@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">User Management</h5>

            <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                + Add User
            </a>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                   <thead class="table-light">
    <tr>
        <th>#</th>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
        <th>Department</th>   {{-- NEW --}}
        <th>Status</th>
        <th class="text-center" width="160">Action</th>
    </tr>
</thead>

                   <tbody>
@forelse($users as $user)
    <tr>
        <td>{{ $loop->iteration }}</td>

        <td>
            <strong>{{ $user->name }}</strong>
        </td>

        <td class="text-muted">
            {{ $user->email }}
        </td>

        <td>
            <span class="badge bg-info text-dark">
                {{ ucfirst($user->role->name ?? '-') }}
            </span>
        </td>

        {{-- DEPARTMENT COLUMN --}}
        <td>
            @if(in_array(optional($user->role)->slug, ['doctor', 'nurse']))
                {{ $user->department->name ?? '-' }}
            @else
                -
            @endif
        </td>

        {{-- STATUS COLUMN --}}
        <td>
            <span class="badge {{ $user->status ? 'bg-success' : 'bg-danger' }}">
                {{ $user->status ? 'Active' : 'Inactive' }}
            </span>
        </td>

        {{-- ACTION COLUMN --}}
        <td class="text-center">
            <a href="{{ route('admin.users.edit', $user) }}"
               class="btn btn-sm btn-outline-warning me-2">
                Edit
            </a>

            <form action="{{ route('admin.users.status', $user) }}"
                  method="POST"
                  class="d-inline">
                @csrf
                @method('PATCH')

                <div class="form-check form-switch d-inline-block align-middle">
                    <input class="form-check-input"
                           type="checkbox"
                           onchange="this.form.submit()"
                           {{ $user->status ? 'checked' : '' }}>
                </div>
            </form>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="text-center text-muted py-4">
            No users found
        </td>
    </tr>
@endforelse
</tbody>

                </table>
            </div>
        </div>
    </div>

</div>
@endsection
