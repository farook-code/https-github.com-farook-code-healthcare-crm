@extends('layouts.dashboard')

@section('header', 'Add Prescription')

@section('content')
<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Add Prescription</h3>
                <p class="mt-1 text-sm text-gray-600">
                    Add a medicine to the prescription for this diagnosis.
                </p>
            </div>
        </div>
        <div class="mt-5 md:mt-0 md:col-span-2">
            <!-- Interaction Alert -->
            <div id="interaction-alert" class="hidden mb-4 bg-red-50 border-l-4 border-red-400 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">
                            <span class="font-bold">Warning:</span> Drug Interaction Detected!
                        </p>
                        <ul id="interaction-list" class="list-disc list-inside mt-1 text-sm text-red-600"></ul>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('doctors.prescription.store', $diagnosis) }}">
                @csrf
                <div class="shadow sm:rounded-md sm:overflow-hidden">
                    <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-6 sm:col-span-6">
                                <label for="medicine_name" class="block text-sm font-medium text-gray-700">Medicine Name</label>
                                <input type="text" name="medicine_name" id="medicine_name" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="dosage" class="block text-sm font-medium text-gray-700">Dosage</label>
                                <input type="text" name="dosage" id="dosage" placeholder="1-0-1" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="duration" class="block text-sm font-medium text-gray-700">Duration</label>
                                <input type="text" name="duration" id="duration" placeholder="5 days" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>

                            <div class="col-span-6">
                                <label for="instructions" class="block text-sm font-medium text-gray-700">Instructions</label>
                                <div class="mt-1">
                                    <textarea id="instructions" name="instructions" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                        <a href="{{ route('doctor.appointments.show', $diagnosis->appointment_id) }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-2">
                            Done
                        </a>
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Add Medicine
                        </button>
                    </div>
                </div>
            </form>
            
            <!-- Existing Prescriptions List -->
             @if($diagnosis->prescriptions->isNotEmpty())
                <div class="mt-8 bg-white shadow sm:rounded-lg overflow-hidden">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Prescriptions Added So Far
                        </h3>
                    </div>
                    <ul class="divide-y divide-gray-200">
                        @foreach($diagnosis->prescriptions as $p)
                            <li class="px-4 py-4 sm:px-6 hover:bg-gray-50 flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-medium text-indigo-600 truncate">{{ $p->medicine_name }}</p>
                                    <p class="text-sm text-gray-500">{{ $p->dosage }} • {{ $p->duration }}</p>
                                    @if($p->instructions)
                                        <p class="text-xs text-gray-400 mt-1 italic">{{ $p->instructions }}</p>
                                    @endif
                                </div>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Added
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
</div>
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const medicineInput = document.getElementById('medicine_name');
        const alertBox = document.getElementById('interaction-alert');
        const alertList = document.getElementById('interaction-list');
        const existingMedicines = @json($diagnosis->prescriptions->pluck('medicine_name'));

        medicineInput.addEventListener('change', function() {
            const newMedicine = this.value.trim();
            if (!newMedicine) return;

            const medicinesToCheck = [...existingMedicines, newMedicine];

            fetch("{{ route('doctor.medicines.check-interactions') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ medicines: medicinesToCheck })
            })
            .then(response => response.json())
            .then(data => {
                alertList.innerHTML = '';
                if (data.interactions && data.interactions.length > 0) {
                    alertBox.classList.remove('hidden');
                    data.interactions.forEach(interaction => {
                        const li = document.createElement('li');
                        li.textContent = `${interaction.medicine.name} + ${interaction.interacting_medicine.name}: ${interaction.severity} - ${interaction.description}`;
                        alertList.appendChild(li);
                    });
                } else {
                    alertBox.classList.add('hidden');
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
</script>
@endsection
@endsection
