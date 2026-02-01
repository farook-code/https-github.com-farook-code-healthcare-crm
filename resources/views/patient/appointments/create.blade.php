@extends('layouts.dashboard')

@section('header', 'Book Appointment')

@section('content')
<div class="max-w-2xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-medium text-gray-900">Request New Appointment</h3>
        </div>
        
        <form action="{{ route('patient.appointments.store') }}" method="POST" class="p-6 space-y-6">
            @csrf
            
            {{-- Doctor Selection --}}
            <div>
                <label for="doctor_id" class="block text-sm font-medium text-gray-700">Select Doctor</label>
                <select name="doctor_id" id="doctor_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">-- Choose a Doctor --</option>
                    @foreach($doctors as $doc)
                        <option value="{{ $doc->id }}">{{ $doc->name }} ({{ $doc->doctorProfile->specialization ?? 'General' }})</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                     <label for="appointment_date" class="block text-sm font-medium text-gray-700">Preferred Date</label>
                     <input type="date" name="appointment_date" id="appointment_date" min="{{ date('Y-m-d') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div>
                     <label for="appointment_time" class="block text-sm font-medium text-gray-700">Preferred Time Slot</label>
                     <div id="slots-container" class="mt-2 grid grid-cols-3 sm:grid-cols-4 gap-2">
                         <p class="text-sm text-gray-500 col-span-3">Select a doctor and date to see available slots.</p>
                     </div>
                     <input type="hidden" name="appointment_time" id="appointment_time" required>
                     @error('appointment_time') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
            </div>

            <script>
                const doctorSelect = document.getElementById('doctor_id');
                const dateInput = document.getElementById('appointment_date');
                const slotsContainer = document.getElementById('slots-container');
                const timeInput = document.getElementById('appointment_time');

                function fetchSlots() {
                    const doctorId = doctorSelect.value;
                    const date = dateInput.value;

                    if (!doctorId || !date) return;

                    slotsContainer.innerHTML = '<p class="text-sm text-gray-400 animate-pulse">Loading slots...</p>';
                    timeInput.value = '';

                    fetch(`{{ route('patient.appointments.slots') }}?doctor_id=${doctorId}&date=${date}`)
                        .then(response => response.json())
                        .then(data => {
                            slotsContainer.innerHTML = '';
                            if (data.slots.length === 0) {
                                slotsContainer.innerHTML = '<p class="text-sm text-red-500 col-span-4">No slots available for this date.</p>';
                                return;
                            }

                            data.slots.forEach(slot => {
                                const btn = document.createElement('button');
                                btn.type = 'button';
                                btn.className = 'px-3 py-2 text-sm font-medium border rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500';
                                btn.textContent = slot; // 09:30
                                btn.onclick = function() {
                                    // Reset sibling buttons
                                    Array.from(slotsContainer.children).forEach(c => {
                                        c.classList.remove('ring-2', 'ring-indigo-500', 'bg-indigo-50', 'text-indigo-700', 'border-indigo-500');
                                        c.classList.add('bg-white', 'text-gray-700', 'border-gray-300');
                                    });
                                    // Select clicked
                                    btn.classList.remove('bg-white', 'text-gray-700', 'border-gray-300');
                                    btn.classList.add('ring-2', 'ring-indigo-500', 'bg-indigo-50', 'text-indigo-700', 'border-indigo-500');
                                    timeInput.value = slot; // Set hidden input
                                };
                                slotsContainer.appendChild(btn);
                            });
                        })
                        .catch(err => {
                            console.error(err);
                            slotsContainer.innerHTML = '<p class="text-sm text-red-500">Error fetching slots.</p>';
                        });
                }

                doctorSelect.addEventListener('change', fetchSlots);
                dateInput.addEventListener('change', fetchSlots);
            </script>
            </div>

            <div>
                <label for="reason" class="block text-sm font-medium text-gray-700">Reason for Visit</label>
                <textarea name="reason" id="reason" rows="3" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Briefly describe your symptoms..."></textarea>
            </div>

            <div class="flex justify-end pt-4">
                <a href="{{ route('patient.appointments.index') }}" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 mr-3">Cancel</a>
                <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Book Appointment
                </button>
            </div>

        </form>
    </div>
</div>
@endsection
