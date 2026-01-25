@extends('layouts.dashboard')

@section('header', 'Calendar')

@section('header', 'Appointment Calendar')

@section('content')
<div class="bg-white shadow rounded-lg p-6">
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-xl font-bold text-gray-800">Visual Scheduler</h2>
        <div class="flex space-x-2">
            <a href="{{ route('reception.appointments.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 text-sm font-medium">
                List View
            </a>
            <a href="{{ route('reception.appointments.create') }}" class="px-4 py-2 bg-indigo-600 rounded-md text-white hover:bg-indigo-700 text-sm font-medium shadow-sm">
                + New Appointment
            </a>
        </div>
    </div>

    <div id='calendar' class="bg-white min-h-[600px]"></div>
</div>

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: "{{ route('reception.appointments.events') }}", // JSON Feed
            eventClick: function(info) {
                alert('Appointment: ' + info.event.title + '\nStatus: ' + info.event.extendedProps.status);
            },
            height: 'auto',
            navLinks: true, 
            editable: true, // Allow Drag and Drop
            dayMaxEvents: true, 
            
            eventDrop: function(info) {
                var newStart = info.event.start.toISOString();
                var eventId = info.event.id;
                
                // Show confirming toast/alert or just proceed?
                // For now, silent update with error revert
                
                fetch("{{ route('reception.appointments.move') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        id: eventId,
                        start: newStart
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        info.revert();
                    } else {
                        // Optional: Show success message (using primitive alert for now or custom Toast if available)
                        // alert(data.message); 
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('System error. Check console.');
                    info.revert();
                });
            },
        });
        calendar.render();
    });
</script>
@endpush
@endsection
