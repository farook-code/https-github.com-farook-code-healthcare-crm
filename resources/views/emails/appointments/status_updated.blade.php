<x-mail::message>
# Appointment Status Update

Dear {{ $appointment->patient->name }},

The status of your appointment with Dr. {{ $appointment->doctor->name }} on {{ $appointment->appointment_date }} has changed.

**New Status:** {{ ucfirst($appointment->status) }}

<x-mail::button :url="route('patient.appointments.index')">
Check Status
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
