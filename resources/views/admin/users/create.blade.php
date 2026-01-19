@extends('layouts.app')
@section('content')
<div class="container mt-4">

    <h4>Create User</h4>

    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Role</label>
          <select name="role_id" class="form-control" required>
    <option value="">Select Role</option>
    @foreach($roles as $role)
        <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
    @endforeach
</select>

        </div>

        <button type="submit" class="btn btn-success">Create User</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Back</a>
    </form>

</div>
@endsection
