<x-dashboard-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Subscription Plans') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="text-center mb-12">
                <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white sm:text-5xl sm:tracking-tight lg:text-6xl">
                    Choose Your Plan
                </h1>
                <p class="mt-5 max-w-xl mx-auto text-xl text-gray-500 dark:text-gray-400">
                    Unlock the full potential of your healthcare CRM with our flexible subscription tiers.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 lg:gap-8">
                @foreach($plans as $plan)
                    @php
                        $isCurrent = $currentSubscription && $currentSubscription->plan_id == $plan->id;
                        $borderColor = $plan->slug == 'pro' ? 'border-purple-500' : ($plan->slug == 'premium' ? 'border-blue-500' : 'border-gray-200 dark:border-gray-700');
                        $bgColor = $plan->slug == 'pro' ? 'bg-gradient-to-b from-gray-900 to-purple-900' : 'bg-white dark:bg-gray-800';
                        $textColor = $plan->slug == 'pro' ? 'text-white' : 'text-gray-900 dark:text-white';
                    @endphp

                    <div class="flex flex-col rounded-2xl shadow-xl overflow-hidden {{ $borderColor }} border-2 transform transition duration-500 hover:scale-105 relative {{ $bgColor }}">
                        
                        @if($isCurrent)
                            <div class="absolute top-0 right-0 bg-green-500 text-white text-xs font-bold px-3 py-1 rounded-bl-lg uppercase tracking-wider">
                                Current Plan
                            </div>
                        @else
                           @if($plan->slug == 'pro')
                            <div class="absolute top-0 right-0 bg-yellow-400 text-black text-xs font-bold px-3 py-1 rounded-bl-lg uppercase tracking-wider">
                                Best Value
                            </div>
                           @endif
                        @endif

                        <div class="px-6 py-8 sm:p-10 sm:pb-6">
                            <div class="flex justify-center">
                                <span class="inline-flex px-4 py-1 rounded-full text-sm font-semibold tracking-wide uppercase bg-opacity-20 {{ $plan->slug == 'pro' ? 'bg-purple-100 text-purple-100' : 'bg-blue-100 text-blue-600' }}">
                                    {{ $plan->name }}
                                </span>
                            </div>
                            <div class="mt-4 flex justify-center items-baseline text-6xl font-extrabold {{ $textColor }}">
                                ${{ $plan->price }}
                                <span class="ml-1 text-2xl font-medium text-gray-500 dark:text-gray-400">/mo</span>
                            </div>
                        </div>

                        <div class="flex-1 flex flex-col justify-between px-6 pt-6 pb-8 bg-opacity-50 space-y-6 sm:p-10 sm:pt-6">
                            <ul class="space-y-4">
                                @foreach($plan->features as $feature)
                                    @php
                                        // Simple feature mapping (consider moving to a Helper or ViewComposer for DRY)
                                        $featureMap = [
                                            'limit_doctors_1' => '1 Doctor Account',
                                            'limit_doctors_5' => 'Up to 5 Doctors',
                                            'limit_doctors_unlimited' => 'Unlimited Doctors',
                                            'limit_patients_unlimited' => 'Unlimited Patients',
                                            'module_appointments' => 'Appointment Scheduling',
                                            'module_pharmacy_stock' => 'Pharmacy & Stock',
                                            'module_lab_reporting' => 'Lab Reporting',
                                            'module_chat' => 'Chat System',
                                            'module_insurance_claims' => 'Insurance Claims',
                                            'module_multi_branch' => 'Multi-Branch Support',
                                            'limit_branches_unlimited' => 'Unlimited Branches'
                                        ];
                                    @endphp
                                    <li class="flex items-start">
                                        <div class="flex-shrink-0">
                                            <svg class="h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                        <p class="ml-3 text-base {{ $plan->slug == 'pro' ? 'text-gray-200' : 'text-gray-700 dark:text-gray-300' }}">
                                            {{ $featureMap[$feature] ?? ucfirst(str_replace('_', ' ', $feature)) }}
                                        </p>
                                    </li>
                                @endforeach
                            </ul>
                            
                            <div class="mt-8">
                                @if($isCurrent)
                                    <div class="mb-3 text-center">
                                        <span class="text-sm font-semibold {{ $plan->slug == 'pro' ? 'text-gray-300' : 'text-gray-600' }}">
                                            Expires: {{ $currentSubscription->ends_at->format('M d, Y') }}
                                        </span>
                                    </div>
                                    <button disabled 
                                        class="w-full flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-600 cursor-not-allowed opacity-75 shadow-md">
                                        Active Plan
                                    </button>
                                @else
                                    <a href="{{ route('subscriptions.checkout', $plan->id) }}" 
                                        class="w-full flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white transition duration-150 ease-in-out
                                        {{ $plan->slug == 'pro' ? 'bg-purple-600 hover:bg-purple-700' : 'bg-blue-600 hover:bg-blue-700' }} 
                                        shadow-md hover:shadow-lg">
                                        Subscribe
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-dashboard-layout>
