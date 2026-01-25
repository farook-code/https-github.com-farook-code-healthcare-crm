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
                            <div class="mt-1">
                                <textarea id="symptoms" name="symptoms" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md"></textarea>
                            </div>
                        </div>

                        <div>
                            <label for="diagnosis" class="block text-sm font-medium text-gray-700">
                                Diagnosis <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1">
                                <textarea id="diagnosis" name="diagnosis" rows="3" required class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md"></textarea>
                            </div>
                        </div>

                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700">
                                Notes
                            </label>
                            <div class="mt-1">
                                <textarea id="notes" name="notes" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md"></textarea>
                            </div>
                        </div>

                        <div>
                            <label for="outcome" class="block text-sm font-medium text-gray-700">
                                Treatment Outcome
                            </label>
                            <div class="mt-1">
                                <select id="outcome" name="outcome" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">-- Select Outcome --</option>
                                    <option value="Improving">Improving</option>
                                    <option value="Recovered">Recovered</option>
                                    <option value="No Change">No Change</option>
                                    <option value="Worse">Worse</option>
                                    <option value="Critical">Critical</option>
                                </select>
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
