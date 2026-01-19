@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h4 class="fw-bold mb-3">Upload Lab Report</h4>

    <form method="POST"
          action="{{ route('lab-reports.store', $appointment->id) }}"
          enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label">Report Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Report File (PDF / Image)</label>
            <input type="file" name="report_file" class="form-control" required>
        </div>

        <button class="btn btn-primary">
            Upload Report
        </button>
    </form>

</div>
@endsection
