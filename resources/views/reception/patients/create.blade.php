@extends('layouts.dashboard')

@section('header', __('messages.register_patient_title'))

@section('content')
<div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-medium leading-6 text-gray-900">{{ __('messages.personal_information') }}</h3>
                <p class="mt-1 text-sm text-gray-600">
                    {{ __('messages.address_desc') }}
                </p>
                <div class="mt-4">
                     <a href="{{ route('reception.appointments.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">
                        {{ __('messages.back_to_booking') }}
                    </a>
                </div>
            </div>
        </div>
        
        <div class="mt-5 md:mt-0 md:col-span-2">
            <form method="POST" action="{{ route('reception.patients.store') }}">
                @csrf
                <div class="shadow overflow-hidden sm:rounded-md">
                    <div class="px-4 py-5 bg-white sm:p-6">
                        <div class="grid grid-cols-6 gap-6">
                            
                            {{-- Name --}}
                            <div class="col-span-6 sm:col-span-3">
                                <label for="name" class="block text-sm font-medium text-gray-700">{{ __('messages.full_name') }}</label>
                                <input type="text" name="name" id="name" required value="{{ old('name') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>

                            {{-- Email --}}
                            <div class="col-span-6 sm:col-span-3">
                                <label for="email" class="block text-sm font-medium text-gray-700">{{ __('messages.email_address') }}</label>
                                <input type="email" name="email" id="email" required value="{{ old('email') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                <p class="mt-1 text-xs text-gray-500">{{ __('messages.default_password_msg') }}</p>
                            </div>

                            {{-- Phone --}}
                            <div class="col-span-6 sm:col-span-3">
                                <label for="phone" class="block text-sm font-medium text-gray-700">{{ __('messages.phone') }}</label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>

                            {{-- DOB --}}
                            <div class="col-span-6 sm:col-span-3">
                                <label for="dob" class="block text-sm font-medium text-gray-700">{{ __('messages.dob') }}</label>
                                <input type="date" name="dob" id="dob" required value="{{ old('dob') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>

                            {{-- Gender --}}
                            <div class="col-span-6 sm:col-span-3">
                                <label for="gender" class="block text-sm font-medium text-gray-700">{{ __('messages.gender') }}</label>
                                <select id="gender" name="gender" required class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">{{ __('messages.select_gender') }}</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>{{ __('messages.male') }}</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>{{ __('messages.female') }}</option>
                                    <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>{{ __('messages.other') }}</option>
                                </select>
                            </div>

                            {{-- Blood Group --}}
                            <div class="col-span-6 sm:col-span-3">
                                <label for="blood_group" class="block text-sm font-medium text-gray-700">{{ __('messages.blood_group') }}</label>
                                <select id="blood_group" name="blood_group" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">{{ __('messages.select_blood_group') }}</option>
                                    <option value="A+">A+</option>
                                    <option value="A-">A-</option>
                                    <option value="B+">B+</option>
                                    <option value="B-">B-</option>
                                    <option value="AB+">AB+</option>
                                    <option value="AB-">AB-</option>
                                    <option value="O+">O+</option>
                                    <option value="O-">O-</option>
                                </select>
                            </div>

                            {{-- Address --}}
                            <div class="col-span-6">
                                <label for="address" class="block text-sm font-medium text-gray-700">{{ __('messages.street_address') }}</label>
                                <textarea name="address" id="address" rows="3" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('address') }}</textarea>
                            </div>

                            {{-- Insurance --}}
                            <div class="col-span-6 border-t border-gray-200 pt-4 mt-2">
                                <h4 class="text-md font-medium text-gray-900 mb-4">{{ __('messages.insurance_details') }}</h4>
                                <div class="grid grid-cols-6 gap-6">
                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="insurance_provider" class="block text-sm font-medium text-gray-700">{{ __('messages.insurance_provider') }}</label>
                                        <input type="text" name="insurance_provider" id="insurance_provider" value="{{ old('insurance_provider') }}" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="policy_number" class="block text-sm font-medium text-gray-700">{{ __('messages.policy_number') }}</label>
                                        <input type="text" name="policy_number" id="policy_number" value="{{ old('policy_number') }}" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Emergency Contact --}}
                            <div class="col-span-6 border-t border-gray-200 pt-4 mt-2">
                                <h4 class="text-md font-medium text-gray-900 mb-4">{{ __('messages.emergency_contact') }}</h4>
                                <div class="grid grid-cols-6 gap-6">
                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="emergency_contact_name" class="block text-sm font-medium text-gray-700">{{ __('messages.contact_name') }}</label>
                                        <input type="text" name="emergency_contact_name" id="emergency_contact_name" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="emergency_contact_phone" class="block text-sm font-medium text-gray-700">{{ __('messages.contact_phone') }}</label>
                                        <input type="text" name="emergency_contact_phone" id="emergency_contact_phone" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Medical --}}
                            <div class="col-span-6 border-t border-gray-200 pt-4 mt-2">
                                <h4 class="text-md font-medium text-gray-900 mb-4">{{ __('messages.medical_history_preliminary') }}</h4>
                                <div class="grid grid-cols-6 gap-6">
                                     <div class="col-span-6">
                                        <label for="allergies" class="block text-sm font-medium text-gray-700">{{ __('messages.known_allergies') }}</label>
                                        <textarea name="allergies" id="allergies" rows="2" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="{{ __('messages.allergies_placeholder') }}"></textarea>
                                     </div>
                                     <div class="col-span-6">
                                        <label for="chronic_conditions" class="block text-sm font-medium text-gray-700">{{ __('messages.chronic_conditions') }}</label>
                                        <textarea name="chronic_conditions" id="chronic_conditions" rows="2" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="{{ __('messages.conditions_placeholder') }}"></textarea>
                                     </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('messages.register_patient_btn') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
