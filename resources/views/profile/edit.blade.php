<x-dashboard-layout>
    @section('header', 'Profile Settings')

    <div class="space-y-6">
        <div class="p-4 sm:p-8 bg-white dark:bg-slate-800 shadow sm:rounded-lg border border-slate-200 dark:border-slate-700">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="p-4 sm:p-8 bg-white dark:bg-slate-800 shadow sm:rounded-lg border border-slate-200 dark:border-slate-700">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="p-4 sm:p-8 bg-white dark:bg-slate-800 shadow sm:rounded-lg border border-slate-200 dark:border-slate-700">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-dashboard-layout>
