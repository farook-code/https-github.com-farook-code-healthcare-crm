@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="card shadow-sm">
        <div class="card-header">
            <h5>Create Department</h5>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.departments.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Department Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <button class="btn btn-success">Save</button>
                <a href="{{ route('admin.departments.index') }}"
                   class="btn btn-secondary">Back</a>
            </form>
        </div>
    </div>

</div>
@endsection
