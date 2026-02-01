<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f3f4f6; padding: 20px; }
        .container { max-w: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .header { text-align: center; border-bottom: 1px solid #e5e7eb; padding-bottom: 20px; margin-bottom: 20px; }
        .logo { font-size: 24px; font-weight: bold; color: #1e3a8a; }
        .content { color: #374151; line-height: 1.6; }
        .amount { font-size: 32px; font-weight: bold; color: #059669; margin: 20px 0; text-align: center; }
        .table { width: 100%; margin-top: 20px; border-collapse: collapse; }
        .table th, .table td { text-align: left; padding: 10px; border-bottom: 1px solid #e5e7eb; }
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
            <h2>Payment Receipt</h2>
            <p>Dear {{ $invoice->patient->name }},</p>
            <p>Thank you for your payment. We have received the following amount:</p>
            
            <div class="amount">${{ number_format($invoice->amount, 2) }}</div>
            
            <table class="table">
                <tr>
                    <th>Invoice ID</th>
                    <td>#{{ str_pad($invoice->id, 6, '0', STR_PAD_LEFT) }}</td>
                </tr>
                <tr>
                    <th>Date</th>
                    <td>{{ $invoice->paid_at ? $invoice->paid_at->format('M d, Y') : date('M d, Y') }}</td>
                </tr>
                <tr>
                    <th>Service</th>
                    <td>Consultation with Dr. {{ $invoice->appointment->doctor->name }}</td>
                </tr>
            </table>

            <p>If you have any questions, please contact our support team.</p>
            
            <a href="{{ route('login') }}" class="button">View Invoice History</a>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} CareSync. All rights reserved.
        </div>
    </div>
</body>
</html>
