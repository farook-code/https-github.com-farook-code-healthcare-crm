@extends('layouts.dashboard')

@section('content')
<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Doctor Profile – {{ $user->name }}</h3>
                <p class="mt-1 text-sm text-gray-600">
                    Update professional details for the doctor.
                </p>
            </div>
        </div>
        <div class="mt-5 md:mt-0 md:col-span-2">
            <form method="POST" action="{{ route('admin.doctors.profile.update', $user) }}">
                @csrf
                <div class="shadow sm:rounded-md sm:overflow-hidden">
                    <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-6 sm:col-span-4">
                                <label for="department_id" class="block text-sm font-medium text-gray-700">Department</label>
                                <select id="department_id" name="department_id" required class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ optional($profile)->department_id == $dept->id ? 'selected' : '' }}>
                                            {{ $dept->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-span-6 sm:col-span-4">
                                <label for="specialization" class="block text-sm font-medium text-gray-700">Specialization</label>
                                <input type="text" name="specialization" id="specialization" value="{{ $profile->specialization ?? '' }}" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>

                            <div class="col-span-6 sm:col-span-4">
                                <label for="qualification" class="block text-sm font-medium text-gray-700">Qualification</label>
                                <input type="text" name="qualification" id="qualification" value="{{ $profile->qualification ?? '' }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="experience_years" class="block text-sm font-medium text-gray-700">Experience (Years)</label>
                                <input type="number" name="experience_years" id="experience_years" value="{{ $profile->experience_years ?? '' }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="consultation_fee" class="block text-sm font-medium text-gray-700">Consultation Fee</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">$</span>
                                    </div>
                                    <input type="number" name="consultation_fee" id="consultation_fee" value="{{ $profile->consultation_fee ?? '' }}" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md" placeholder="0.00">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Save Profile
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
