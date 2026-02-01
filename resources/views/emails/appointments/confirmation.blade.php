<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f3f4f6; padding: 20px; }
        .container { max-w: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .header { text-align: center; border-bottom: 1px solid #e5e7eb; padding-bottom: 20px; margin-bottom: 20px; }
        .logo { font-size: 24px; font-weight: bold; color: #1e3a8a; }
        .content { color: #374151; line-height: 1.6; }
        .button { display: inline-block; background-color: #2563eb; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-top: 20px; }
        .footer { margin-top: 30px; border-top: 1px solid #e5e7eb; padding-top: 20px; text-align: center; font-size: 12px; color: #9ca3af; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">CareSync</div>
        </div>
        <div class="content">
            <h2>Appointment Confirmed</h2>
            <p>Dear {{ $appointment->patient->name }},</p>
            <p>Your appointment has been successfully scheduled. Here are the details:</p>
            <ul>
                <li><strong>Doctor:</strong> Dr. {{ $appointment->doctor->name }}</li>
                <li><strong>Department:</strong> {{ $appointment->department->name ?? 'General' }}</li>
                <li><strong>Date:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('l, F j, Y') }}</li>
                <li><strong>Time:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</li>
            </ul>
            <p>Please arrive 15 minutes early for registration.</p>
            
            <a href="{{ route('login') }}" class="button">View My Appointments</a>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} CareSync. All rights reserved.
        </div>
    </div>
</body>
</html>
