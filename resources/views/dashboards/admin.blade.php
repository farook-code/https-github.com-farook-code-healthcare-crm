@extends('layouts.dashboard')

@section('header', 'Admin Dashboard')

@section('content')
    @php
        // Defensive coding: ensure stats exist even if controller fails
        $stats = $stats ?? [
            'users' => \App\Models\User::count(),
            'doctors' => \App\Models\DoctorProfile::count(),
            'departments' => \App\Models\Department::count(),
            'appointments' => \App\Models\Appointment::count(),
        ];
        $recentUsers = $recentUsers ?? collect([]);
        $recentAppointments = $recentAppointments ?? collect([]);
    @endphp

    <div class="space-y-6">
        <!-- Stats Overview -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            
            <!-- Users Card -->
            <div class="bg-white overflow-hidden shadow rounded-lg hover:shadow-md transition-shadow">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                            <!-- Heroicon name: users -->
                            <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Users</dt>
                                <dd class="text-2xl font-semibold text-gray-900">{{ $stats['users'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-3">
                    <div class="text-sm">
                        <a href="{{ route('admin.users.index') }}" class="font-medium text-indigo-700 hover:text-indigo-900">View all</a>
                    </div>
                </div>
            </div>

            <!-- Doctors Card -->
            <div class="bg-white overflow-hidden shadow rounded-lg hover:shadow-md transition-shadow">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                             <!-- Heroicon name: user-group (doctor representation) -->
                             <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                             </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Doctors</dt>
                                <dd class="text-2xl font-semibold text-gray-900">{{ $stats['doctors'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                 <div class="bg-gray-50 px-5 py-3">
                    <div class="text-sm">
                        <a href="{{ route('admin.doctors.index') }}" class="font-medium text-green-700 hover:text-green-900">View all</a>
                    </div>
                </div>
            </div>

             <!-- Departments Card -->
            <div class="bg-white overflow-hidden shadow rounded-lg hover:shadow-md transition-shadow">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                             <!-- Heroicon name: office-building -->
                             <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                             </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Departments</dt>
                                <dd class="text-2xl font-semibold text-gray-900">{{ $stats['departments'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                 <div class="bg-gray-50 px-5 py-3">
                    <div class="text-sm">
                        <a href="{{ route('admin.departments.index') }}" class="font-medium text-purple-700 hover:text-purple-900">View all</a>
                    </div>
                </div>
            </div>

            <!-- Appointments Card -->
             <div class="bg-white overflow-hidden shadow rounded-lg hover:shadow-md transition-shadow">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-orange-500 rounded-md p-3">
                             <!-- Heroicon name: calendar -->
                             <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                             </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Appointments</dt>
                                <dd class="text-2xl font-semibold text-gray-900">{{ $stats['appointments'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                 <div class="bg-gray-50 px-5 py-3">
                    <div class="text-sm">
                         <a href="{{ route('admin.reports.index') }}" class="font-medium text-orange-700 hover:text-orange-900">View details</a>
                    </div>
                </div>
            </div>

        </div>

        <!-- Analytics Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white px-5 py-6 shadow rounded-lg sm:px-6">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Revenue Growth</h3>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        +12.5%
                    </span>
                </div>
                <div class="relative h-64 w-full">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <div class="bg-white px-5 py-6 shadow rounded-lg sm:px-6">
                <div class="mb-4">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Appointment Status</h3>
                </div>
                <div class="relative h-64 w-full flex justify-center">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Users -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Latest Users</h3>
                </div>
                <ul class="divide-y divide-gray-100">
                    @forelse($recentUsers as $user)
                        <li class="px-5 py-3 flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-bold shrink-0">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $user->role->name ?? 'User' }}</div>
                                </div>
                            </div>
                            <div class="text-xs text-gray-400">
                                {{ $user->created_at->diffForHumans() }}
                            </div>
                        </li>
                    @empty
                        <li class="px-5 py-6 text-center text-gray-500">No users found.</li>
                    @endforelse
                </ul>
                <div class="bg-gray-50 px-5 py-2 text-center rounded-b-lg">
                    <a href="{{ route('admin.users.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">View all users</a>
                </div>
            </div>

            <!-- Recent Appointments -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Recent Appointments</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($recentAppointments as $appt)
                                <tr>
                                    <td class="px-5 py-3 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ optional($appt->patient)->name ?? 'Unknown' }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            w/ {{ optional($appt->doctor)->name ?? 'Doctor' }}
                                        </div>
                                    </td>
                                    <td class="px-5 py-3 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $appt->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                             {{ $appt->status === 'scheduled' ? 'bg-blue-100 text-blue-800' : '' }}
                                             {{ $appt->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                            {{ ucfirst($appt->status) }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3 whitespace-nowrap text-xs text-gray-500 text-right">
                                        {{ \Carbon\Carbon::parse($appt->appointment_date)->format('M d') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-5 py-6 text-center text-gray-500">No appointments yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                 <div class="bg-gray-50 px-5 py-2 text-center rounded-b-lg">
                    <a href="{{ route('admin.reports.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">View detailed reports</a>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <h3 class="text-lg leading-6 font-medium text-gray-900 mt-8">Quick Actions</h3>
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            
            <div class="bg-white overflow-hidden shadow rounded-lg p-6 flex flex-col items-start hover:bg-gray-50 transition">
                <h4 class="text-base font-bold text-gray-900 mb-2">Manage Users</h4>
                <p class="text-sm text-gray-500 mb-4">Add, edit, or remove users (doctors, patients, staff).</p>
                <a href="{{ route('admin.users.index') }}" class="mt-auto inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none">
                    Go to Users
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg p-6 flex flex-col items-start hover:bg-gray-50 transition">
                <h4 class="text-base font-bold text-gray-900 mb-2">System Reports</h4>
                <p class="text-sm text-gray-500 mb-4">Check financial reports and appointment analytics.</p>
                <a href="{{ route('admin.reports.index') }}" class="mt-auto inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none">
                    View Reports
                </a>
            </div>
            
             <div class="bg-white overflow-hidden shadow rounded-lg p-6 flex flex-col items-start hover:bg-gray-50 transition">
                <h4 class="text-base font-bold text-gray-900 mb-2">Audit Logs</h4>
                <p class="text-sm text-gray-500 mb-4">Monitor security and system changes in real-time.</p>
                <a href="{{ route('admin.logs.index') }}" class="mt-auto inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-gray-600 hover:bg-gray-700 focus:outline-none">
                    Check Logs
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg p-6 flex flex-col items-start hover:bg-gray-50 transition">
                <h4 class="text-base font-bold text-gray-900 mb-2">Messages</h4>
                <p class="text-sm text-gray-500 mb-4">Read and reply to messages from staff and patients.</p>
                <a href="{{ route('chat.index') }}" class="mt-auto inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none">
                    Open Chat
                </a>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Revenue Chart (Line)
        const ctxRev = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctxRev, {
            type: 'line',
            data: {
                labels: {!! json_encode($months) !!},
                datasets: [{
                    label: 'Revenue ($)',
                    data: {!! json_encode($revenueData) !!},
                    borderColor: '#4f46e5', // Indigo 600
                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { display: false }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });

        // Status Chart (Doughnut)
        const ctxStatus = document.getElementById('statusChart').getContext('2d');
        // Data from Controller: [completed: X, scheduled: Y, cancelled: Z]
        const statusData = {!! json_encode($appointmentStatus) !!};
        
        new Chart(ctxStatus, {
            type: 'doughnut',
            data: {
                labels: ['Completed', 'Scheduled', 'Cancelled'],
                datasets: [{
                    data: [statusData.completed, statusData.scheduled, statusData.cancelled],
                    backgroundColor: [
                        '#10b981', // Emerald 500
                        '#3b82f6', // Blue 500
                        '#ef4444'  // Red 500
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { usePointStyle: true, padding: 20 }
                    }
                }
            }
        });
    });
</script>
@endpush
