@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h4>Edit User</h4>

    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf
        @method('PUT')

        <div class="mb-2">
            <label>Name</label>
            <input name="name" class="form-control"
                   value="{{ $user->name }}" required>
        </div>

        <div class="mb-2">
            <label>Email</label>
            <input name="email" type="email" class="form-control"
                   value="{{ $user->email }}" required>
        </div>

        <div class="mb-3">
            <label>Role</label>
            <select name="role_id" class="form-control" required>
    @foreach($roles as $role)
        <option value="{{ $role->id }}"
            {{ $user->role_id == $role->id ? 'selected' : '' }}>
            {{ ucfirst($role->name) }}
        </option>
    @endforeach
</select>

        </div>

        @if(in_array(optional($user->role)->slug, ['doctor', 'nurse']))
    <div class="mb-3">
        <label>Department</label>
        <select name="department_id" class="form-control">
            <option value="">-- Select Department --</option>

            @foreach($departments as $dept)
                <option value="{{ $dept->id }}"
                    {{ $user->department_id == $dept->id ? 'selected' : '' }}>
                    {{ $dept->name }}
                </option>
            @endforeach
        </select>
    </div>
@endif


        <button class="btn btn-primary">Update</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
