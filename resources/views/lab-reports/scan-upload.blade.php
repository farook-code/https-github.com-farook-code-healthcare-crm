@extends('layouts.dashboard')

@section('header', 'Quick Scan & Upload')

@section('content')
<div class="max-w-3xl mx-auto py-10 px-4">
    
    <div class="text-center mb-10">
        <h2 class="text-2xl font-bold text-gray-900">Scan Patient Barcode</h2>
        <p class="text-gray-500">Use the barcode scanner on the Patient ID Card or Appointment Slip.</p>
    </div>

    <div class="bg-white shadow-lg rounded-xl overflow-hidden mb-8">
        <div class="p-8">
            <div class="max-w-lg mx-auto">
                <label for="scanner_input" class="sr-only">Barcode Input</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                    </div>
                    <input type="text" id="scanner_input" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-12 py-4 sm:text-lg border-gray-300 rounded-lg bg-gray-50 focus:bg-white transition-colors" placeholder="Click here and scan barcode..." autofocus autocomplete="off">
                </div>
                <div id="loading_indicator" class="hidden mt-2 text-center text-sm text-indigo-600 font-medium animate-pulse">
                    Looking up patient...
                </div>
            </div>
        </div>
        
        {{-- Result Area --}}
        <div id="patient_result" class="hidden border-t border-gray-100 bg-indigo-50 p-6 animate-fade-in-down">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="h-12 w-12 rounded-full bg-indigo-200 flex items-center justify-center text-indigo-700 font-bold text-lg" id="res_avatar">
                        ?
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-lg font-bold text-gray-900" id="res_name">Patient Name</h3>
                    <p class="text-sm text-indigo-700">Patient ID: <span id="res_id">#000</span></p>
                    
                    <div class="mt-4">
                        <form id="upload_form" action="" method="POST" enctype="multipart/form-data" class="bg-white p-4 rounded-lg shadow-sm border border-indigo-100">
                            @csrf
                            <input type="hidden" name="patient_id" id="form_patient_id">
                            
                            {{-- We need a generic manual store route OR allow null appointment --}}
                            {{-- For now: Create a dummy appointment or we need to update Store method to allow NULL appointment --}}
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Report Title</label>
                                    <input type="text" name="title" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Select File</label>
                                    <input type="file" name="report_file" required class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                </div>
                                <div class="pt-2">
                                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Upload Report
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="ml-4 flex-shrink-0">
                    <button type="button" onclick="resetScanner()" class="bg-white rounded-md p-1 text-gray-400 hover:text-gray-500 focus:outline-none">
                        <span class="sr-only">Close</span>
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-blue-700">
                    Scanner Tips: Ensure the input box is focused before scanning. The scanner acts as a keyboard.
                </p>
            </div>
        </div>
    </div>

</div>

<script>
    let timeout = null;
    const input = document.getElementById('scanner_input');
    
    input.addEventListener('keyup', function(e) {
        clearTimeout(timeout);
        
        // Barcode scanners usually end with Enter (13)
        if (e.keyCode === 13 && this.value.length > 0) {
            lookupPatient(this.value);
            return;
        }

        // Auto-lookup after delay if typing manually
        timeout = setTimeout(() => {
            if (this.value.length > 3) {
                lookupPatient(this.value);
            }
        }, 500); // 500ms delay for manual typing
    });

    function lookupPatient(term) {
        document.getElementById('loading_indicator').classList.remove('hidden');
        
        fetch(`{{ route('lab-reports.scan-lookup') }}?term=${term}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('loading_indicator').classList.add('hidden');
                
                if (data.success) {
                    showResult(data.patient);
                } else {
                    alert('Patient not found! Please try scanning again.');
                    input.value = '';
                    input.focus();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('loading_indicator').classList.add('hidden');
            });
    }

    function showResult(patient) {
        const resultDiv = document.getElementById('patient_result');
        document.getElementById('res_name').textContent = patient.name;
        document.getElementById('res_id').textContent = '#' + patient.id;
        document.getElementById('res_avatar').textContent = patient.name.charAt(0);
        
        // Update Form Action to a NEW route that doesn't require Appointment ID
        // Note: We need to create a storeDirect route in controller
        const form = document.getElementById('upload_form');
        form.action = "{{ route('lab-reports.store-direct') }}"; 
        
        // Populate hidden ID
        document.getElementById('form_patient_id').value = patient.id;
        
        resultDiv.classList.remove('hidden');
        input.disabled = true;
    }

    function resetScanner() {
        document.getElementById('patient_result').classList.add('hidden');
        input.disabled = false;
        input.value = '';
        input.focus();
    }
    
    // Auto-focus on load
    window.onload = function() {
        input.focus();
    };
</script>
@endsection
