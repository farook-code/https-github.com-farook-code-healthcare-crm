@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">My Prescriptions</h2>
        <a href="{{ route('patient.dashboard') }}" class="btn btn-outline-secondary btn-sm">
            ← Back to Dashboard
        </a>
    </div>

    <div class="card">
        <div class="card-body p-0">

            @if($prescriptions->count() === 0)
                <div class="p-4 text-center text-muted">
                    No prescriptions found.
                </div>
            @else
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Doctor</th>
                            <th>Prescription</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($prescriptions as $prescription)
                            <tr>
                                <td>
                                    {{ optional($prescription->created_at)->format('Y-m-d') }}
                                </td>
                                <td>
                                    {{ $prescription->diagnosis->appointment->doctor->name ?? 'N/A' }}
                                </td>
                                <td>
                                    <pre class="mb-0" style="white-space: pre-wrap;">
{{ $prescription->details }}
                                    </pre>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

        </div>
    </div>

    <div class="mt-3">
        {{ $prescriptions->links() }}
    </div>

</div>
@endsection
