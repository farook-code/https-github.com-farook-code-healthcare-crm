@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between">
            <h5 class="mb-0">Departments</h5>
            <a href="{{ route('admin.departments.create') }}"
               class="btn btn-primary btn-sm">+ Add Department</a>
        </div>

        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th width="120">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($departments as $dept)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $dept->name }}</td>
                            <td>
                                <span class="badge {{ $dept->status ? 'bg-success' : 'bg-danger' }}">
                                    {{ $dept->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
         <td class="text-center">
    <form method="POST"
          action="{{ route('admin.departments.status', $dept) }}"
          class="d-inline">
        @csrf
        @method('PATCH')

        <div class="form-check form-switch d-inline-block">
            <input class="form-check-input"
                   type="checkbox"
                   onchange="this.form.submit()"
                   {{ $dept->status ? 'checked' : '' }}>
        </div>
    </form>
</td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
