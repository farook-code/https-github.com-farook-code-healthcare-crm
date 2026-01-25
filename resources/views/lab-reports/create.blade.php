@extends('layouts.dashboard')

@section('header', 'Upload Lab Report')

@section('content')
<div class="max-w-2xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Upload Lab Report
            </h3>
        </div>
        
        <div class="p-6">
            <form method="POST"
                  action="{{ route('lab-reports.store', $appointment->id) }}"
                  enctype="multipart/form-data">
                @csrf
        
                <div class="space-y-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Report Title</label>
                        <div class="mt-1">
                            <input type="text" name="title" id="title" required class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        </div>
                    </div>
        
                    <div>
                        <label for="report_file" class="block text-sm font-medium text-gray-700">Report File (PDF / Image)</label>
                        <div class="mt-1">
                            <input type="file" name="report_file" id="report_file" required class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md p-2 border">
                        </div>
                    </div>
        
                    <div>
                        <button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Upload Report
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
