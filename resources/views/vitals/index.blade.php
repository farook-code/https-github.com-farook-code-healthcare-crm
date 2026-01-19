@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">My Vitals</h2>
    </div>

    {{-- Vitals Table --}}
    <div class="card shadow-sm">
        <div class="card-header fw-semibold bg-light">
            Vitals History
        </div>

        <div class="card-body p-0">
            @if($vitals->count())
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mb-0 align-middle">
                       <thead class="table-light">
<tr>
    <th>Date</th>
    <th>Blood Pressure</th>
    <th>Pulse</th>
    <th>Temperature</th>
    <th>SpO₂</th>
    <th>Recorded By</th>
</tr>
</thead>

                      <tbody>
@foreach($vitals as $vital)
<tr>
    <td>{{ $vital->created_at->format('d M Y, h:i A') }}</td>
    <td>{{ $vital->blood_pressure ?? '-' }}</td>
    <td>{{ $vital->pulse ?? '-' }}</td>
    <td>{{ $vital->temperature ?? '-' }}</td>
    <td>{{ $vital->oxygen_level ?? '-' }}</td>
    <td>
        {{ $vital->recorder->name ?? 'N/A' }}
        <span class="badge ms-2
            {{ $vital->recorder->role === 'doctor' ? 'bg-success' : '' }}
            {{ $vital->recorder->role === 'nurse' ? 'bg-primary' : '' }}">
            {{ ucfirst($vital->recorder->role ?? '') }}
        </span>
    </td>
</tr>
@endforeach
</tbody>

                    </table>
                </div>
            @else
                <div class="text-center py-4 text-muted">
                    No vitals recorded yet.
                </div>
            @endif
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-3">
        {{ $vitals->links() }}
    </div>

</div>
@endsection
