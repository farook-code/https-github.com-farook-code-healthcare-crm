@extends('layouts.dashboard')

@section('header')
<div class="flex items-center justify-between w-full">
    <h2 class="text-xl font-bold leading-tight text-slate-800 dark:text-gray-200">
        {{ __('Analytics Dashboard') }}
    </h2>
    
    <select id="rangeParams" class="text-sm border-slate-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-slate-700 dark:border-slate-600 dark:text-white">
        <option value="7_days">Last 7 Days</option>
        <option value="30_days" selected>Last 30 Days</option>
        <option value="90_days">Last 90 Days</option>
        <option value="this_year">This Year</option>
    </select>
</div>
@endsection

@section('content')
<div class="space-y-6">
    
    <!-- Top Stats Row (Placeholder for Summary Cards if needed, using Charts for now) -->
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Revenue Chart -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-6 border border-slate-100 dark:border-slate-700">
            <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-4">Revenue Trend</h3>
            <div class="relative h-64">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Appointment Status -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-6 border border-slate-100 dark:border-slate-700">
            <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-4">Appointment Outcome</h3>
             <div class="relative h-64 flex justify-center">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
        
        <!-- Top Doctors -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-6 border border-slate-100 dark:border-slate-700">
            <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-4">Top Performing Doctors (Revenue)</h3>
             <div class="relative h-64">
                <canvas id="doctorChart"></canvas>
            </div>
        </div>

        <!-- Patient Growth -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-6 border border-slate-100 dark:border-slate-700">
            <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-4">Patient Acquisition</h3>
             <div class="relative h-64">
                <canvas id="patientChart"></canvas>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const rangeSelect = document.getElementById('rangeParams');
        
        // Chart Instances
        let revenueChart, statusChart, doctorChart, patientChart;

        function initCharts() {
            // Common Options
            const commonOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { labels: { color: document.documentElement.classList.contains('dark') ? '#cbd5e1' : '#475569' } }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                         grid: { color: document.documentElement.classList.contains('dark') ? '#334155' : '#e2e8f0' },
                         ticks: { color: document.documentElement.classList.contains('dark') ? '#cbd5e1' : '#475569' }
                    },
                    x: {
                         grid: { display: false },
                         ticks: { color: document.documentElement.classList.contains('dark') ? '#cbd5e1' : '#475569' }
                    }
                }
            };

            // Revenue
            const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
            revenueChart = new Chart(ctxRevenue, {
                type: 'line',
                data: { labels: [], datasets: [] },
                options: commonOptions
            });

            // Status
            const ctxStatus = document.getElementById('statusChart').getContext('2d');
            statusChart = new Chart(ctxStatus, {
                type: 'doughnut',
                data: { labels: [], datasets: [] },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                         legend: { position: 'bottom', labels: { color: document.documentElement.classList.contains('dark') ? '#cbd5e1' : '#475569' } }
                    }
                }
            });
            
             // Doctors
            const ctxDoctor = document.getElementById('doctorChart').getContext('2d');
            doctorChart = new Chart(ctxDoctor, {
                type: 'bar',
                data: { labels: [], datasets: [] },
                options: commonOptions
            });
            
            // Patients
            const ctxPatient = document.getElementById('patientChart').getContext('2d');
            patientChart = new Chart(ctxPatient, {
                type: 'line',
                data: { labels: [], datasets: [] },
                options: commonOptions
            });
        }

        function fetchData(range) {
            fetch(`{{ route('analytics.data') }}?range=${range}`)
                .then(response => response.json())
                .then(data => {
                    updateCharts(data);
                })
                .catch(error => console.error('Error fetching analytics:', error));
        }

        function updateCharts(data) {
            // Update Revenue
            revenueChart.data.labels = data.revenue.labels;
            revenueChart.data.datasets = [{
                label: 'Revenue ($)',
                data: data.revenue.data,
                borderColor: '#4f46e5',
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                fill: true,
                tension: 0.4
            }];
            revenueChart.update();

            // Update Status
            const statusColors = {
                'completed': '#10b981',
                'pending': '#f59e0b',
                'cancelled': '#ef4444',
                'scheduled': '#3b82f6',
                'no_show': '#64748b'
            };
            statusChart.data.labels = data.status.labels.map(l => l.charAt(0).toUpperCase() + l.slice(1));
            statusChart.data.datasets = [{
                data: data.status.data,
                backgroundColor: data.status.labels.map(l => statusColors[l] || '#cbd5e1'),
                borderWidth: 0
            }];
            statusChart.update();
            
            // Update Doctors
            doctorChart.data.labels = data.doctors.labels;
            doctorChart.data.datasets = [{
                label: 'Revenue Generated ($)',
                data: data.doctors.data,
                backgroundColor: '#8b5cf6',
                borderRadius: 4
            }];
            doctorChart.update();
            
            // Update Patients
            patientChart.data.labels = data.patients.labels;
            patientChart.data.datasets = [{
                label: 'New Patients',
                data: data.patients.data,
                borderColor: '#ec4899',
                backgroundColor: 'rgba(236, 72, 153, 0.1)',
                fill: true,
                tension: 0.4
            }];
            patientChart.update();
        }

        // Init
        initCharts();
        fetchData('30_days');

        // Listener
        rangeSelect.addEventListener('change', (e) => {
            fetchData(e.target.value);
        });
    });
</script>
@endpush
@endsection
