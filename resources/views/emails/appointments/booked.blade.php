<x-mail::message>
# Appointment Confirmation

Dear {{ $appointment->patient->name }},

Your appointment has been successfully booked.

**Datails:**
- **Doctor:** Dr. {{ $appointment->doctor->name }}
- **Date:** {{ $appointment->appointment_date }}
- **Time:** {{ $appointment->appointment_time }}
- **Department:** {{ $appointment->department->name }}

Please arrive 15 minutes early.

<x-mail::button :url="route('patient.appointments.index')">
View Appointment
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
