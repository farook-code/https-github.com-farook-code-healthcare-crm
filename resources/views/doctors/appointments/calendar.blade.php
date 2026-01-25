@extends('layouts.dashboard')

@section('header', 'My Calendar')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white shadow rounded-lg p-6">
        <div class="mb-6 flex justify-between items-center">
            <h2 class="text-xl font-bold text-gray-800">My Schedule</h2>
            <div class="flex space-x-2">
                <a href="{{ route('doctor.appointments') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 text-sm font-medium">
                    List View
                </a>
            </div>
        </div>

        <div id='calendar' class="bg-white min-h-[600px]"></div>
    </div>
</div>

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridWeek', // Doctors usually prefer week view
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: "{{ route('doctor.appointments.events') }}",
            height: 'auto',
            navLinks: true,
            slotMinTime: '08:00:00',
            slotMaxTime: '20:00:00',
            allDaySlot: false,
            nowIndicator: true,
            eventClick: function(info) {
                if (info.event.url) {
                    info.jsEvent.preventDefault(); // don't let the browser navigate
                    window.location.href = info.event.url;
                }
            }
        });
        calendar.render();
    });
</script>
@endpush
@endsection
