@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h5>Doctors</h5>
    </div>

    <table class="table mb-0">
        <thead>
            <tr>
                <th>Name</th>
                <th>Department</th>
                <th>Profile</th>
            </tr>
        </thead>
        <tbody>
            @foreach($doctors as $doctor)
                <tr>
                    <td>{{ $doctor->name }}</td>
                    <td>
                        {{ $doctor->doctorProfile->department->name ?? '-' }}
                    </td>
                    <td>
                        <a href="{{ route('admin.doctors.profile.edit', $doctor) }}"
                           class="btn btn-sm btn-primary">
                            Assign / Edit
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
