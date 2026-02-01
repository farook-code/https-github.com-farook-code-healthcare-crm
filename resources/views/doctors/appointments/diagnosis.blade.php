@extends('layouts.dashboard')

@section('header', 'Add Diagnosis')

@section('content')
<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Add Diagnosis</h3>
                <p class="mt-1 text-sm text-gray-600">
                    Record diagnosis details for the appointment.
                </p>
            </div>
        </div>
        <div class="mt-5 md:mt-0 md:col-span-2">
            <form method="POST" action="{{ route('doctors.appointments.diagnosis.store', $appointment) }}">
                @csrf
                <div class="shadow sm:rounded-md sm:overflow-hidden">
                    <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                        <div>
                            <label for="symptoms" class="block text-sm font-medium text-gray-700">
                                Symptoms
                            </label>
                            <div class="mt-1 relative">
                                <textarea id="symptoms" name="symptoms" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md">{{ old('symptoms', $appointment->diagnosis->symptoms ?? '') }}</textarea>
                                <button type="button" onclick="toggleRecording('symptoms')" id="btn-symptoms" class="absolute top-2 right-2 text-gray-400 hover:text-red-500 transition-colors p-1 rounded-full border border-transparent hover:bg-gray-100" title="AI Scribe (Voice to Text)">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path></svg>
                                </button>
                            </div>

    @push('scripts')
    <script>
        let recognition = null;
        let currentTarget = null;
        let isRecording = false;

        if ('webkitSpeechRecognition' in window) {
            recognition = new webkitSpeechRecognition();
            recognition.continuous = false;
            recognition.interimResults = false;
            recognition.lang = 'en-US';

            recognition.onstart = function() {
                isRecording = true;
                if(currentTarget) {
                    const btn = document.getElementById('btn-' + currentTarget);
                    btn.classList.add('text-red-600', 'animate-pulse');
                    btn.classList.remove('text-gray-400');
                }
            };

            recognition.onend = function() {
                isRecording = false;
                if(currentTarget) {
                    const btn = document.getElementById('btn-' + currentTarget);
                    btn.classList.remove('text-red-600', 'animate-pulse');
                    btn.classList.add('text-gray-400');
                    currentTarget = null;
                }
            };

            recognition.onerror = function(event) {
                console.error("Speech recognition error", event.error);
                alert("Microphone Error: " + event.error + ". Please ensure site has permission (Check browser address bar lock icon).");
                isRecording = false;
            };

            recognition.onresult = function(event) {
                const transcript = event.results[0][0].transcript;
                if(currentTarget) {
                     const field = document.getElementById(currentTarget);
                     // Append text if already has content
                     field.value = field.value ? field.value + ' ' + transcript : transcript;
                }
            };
        } else {
             console.warn("Web Speech API not supported in this browser.");
        }

        function toggleRecording(targetId) {
            if (!recognition) {
                alert("Your browser does not support AI Voice Typing. Please use Chrome/Edge.");
                return;
            }

            if (isRecording) {
                recognition.stop();
                return;
            }

            currentTarget = targetId;
            recognition.start();
        }
    </script>
    @endpush
                        </div>

                        <div>
                            <label for="diagnosis" class="block text-sm font-medium text-gray-700">
                                Diagnosis <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1">
                                <textarea id="diagnosis" name="diagnosis" rows="3" required class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md">{{ old('diagnosis', $appointment->diagnosis->diagnosis ?? '') }}</textarea>
                            </div>
                        </div>

                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700">
                                Notes
                            </label>
                            <div class="mt-1">
                                <textarea id="notes" name="notes" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md">{{ old('notes', $appointment->diagnosis->notes ?? '') }}</textarea>
                            </div>
                        </div>

                        <div>
                            <label for="outcome" class="block text-sm font-medium text-gray-700">
                                Treatment Outcome
                            </label>
                            <div class="mt-1">
                                <select id="outcome" name="outcome" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">-- Select Outcome --</option>
                                    @php $out = old('outcome', $appointment->diagnosis->outcome ?? ''); @endphp
                                    <option value="Improving" {{ $out == 'Improving' ? 'selected' : '' }}>Improving</option>
                                    <option value="Recovered" {{ $out == 'Recovered' ? 'selected' : '' }}>Recovered</option>
                                    <option value="No Change" {{ $out == 'No Change' ? 'selected' : '' }}>No Change</option>
                                    <option value="Worse" {{ $out == 'Worse' ? 'selected' : '' }}>Worse</option>
                                    <option value="Critical" {{ $out == 'Critical' ? 'selected' : '' }}>Critical</option>
                                </select>
                            </div>
                        </div>

                        <!-- Patient Journey / Recommendation -->
                        <div class="bg-blue-50 p-4 rounded-md border border-blue-100">
                            <label class="block text-sm font-medium text-blue-900 mb-2">
                                Patient Journey / Next Steps (Recommendation)
                            </label>
                            @php $rec = old('recommended_action', $appointment->diagnosis->follow_up_action ?? 'OPD Follow-up'); @endphp
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <input id="journey_opd" name="recommended_action" type="radio" value="OPD Follow-up" {{ $rec == 'OPD Follow-up' ? 'checked' : '' }} class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                    <label for="journey_opd" class="ml-3 block text-sm font-medium text-gray-700">
                                        OPD (Outpatient - Prescribe Medicine & Follow up)
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input id="journey_ipd" name="recommended_action" type="radio" value="Suggest Admission (IPD)" {{ $rec == 'Suggest Admission (IPD)' ? 'checked' : '' }} class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                    <label for="journey_ipd" class="ml-3 block text-sm font-medium text-gray-700">
                                        Suggest Admission (IPD - In-Patient Care)
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input id="journey_ot" name="recommended_action" type="radio" value="Suggest Surgery (OT)" {{ $rec == 'Suggest Surgery (OT)' ? 'checked' : '' }} class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                    <label for="journey_ot" class="ml-3 block text-sm font-medium text-gray-700">
                                        Suggest Surgery / Operation (OT)
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                        <a href="{{ route('doctor.appointments.show', $appointment->id) }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-2">
                            Cancel
                        </a>
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Save Diagnosis
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
