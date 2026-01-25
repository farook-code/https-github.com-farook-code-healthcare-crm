@extends('layouts.dashboard')

@section('header', 'My Health ID')

@section('content')
<div class="max-w-md mx-auto py-10 px-4">
    
    <div class="bg-gradient-to-br from-indigo-600 to-blue-700 rounded-2xl shadow-2xl overflow-hidden text-white relative">
        {{-- Ornament --}}
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-48 h-48 bg-white opacity-10 rounded-full blur-2xl"></div>
        
        <div class="p-8 pb-12 text-center relative z-10">
            <h2 class="text-sm font-bold tracking-widest uppercase opacity-75 mb-2">HealthFlow Clinic</h2>
            
            {{-- Profile Photo --}}
            <div class="w-24 h-24 mx-auto bg-white rounded-full p-1 shadow-lg mb-4">
                 <div class="w-full h-full rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-3xl">
                     {{ substr($patient->name, 0, 1) }}
                 </div>
            </div>

            <h1 class="text-2xl font-bold mb-1">{{ $patient->name }}</h1>
            <p class="text-indigo-200 text-sm mb-6">ID: #{{ str_pad($patient->id, 8, '0', STR_PAD_LEFT) }}</p>

            {{-- QR Code Area --}}
            <div class="bg-white p-4 rounded-xl shadow-inner inline-block mx-auto">
                <canvas id="qrcode-canvas"></canvas>
            </div>
            
            <p class="mt-6 text-xs text-indigo-300">Scan this at the reception kiosk to check-in instantly.</p>
        </div>

        {{-- Footer --}}
        <div class="bg-black bg-opacity-20 p-4 text-center text-xs font-mono text-indigo-200">
             Valid Member • {{ date('Y') }}
        </div>
    </div>

    <div class="mt-8 text-center">
        <button onclick="window.print()" class="text-indigo-600 hover:text-indigo-800 font-medium text-sm flex items-center justify-center mx-auto">
             <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
             Download / Print Card
        </button>
    </div>

</div>

{{-- QR Code Library --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        new QRious({
            element: document.getElementById('qrcode-canvas'),
            value: '{{ $patient->id }}', // The data to scan (Patient ID)
            size: 150,
            foreground: '#1e1b4b'
        });
    });
</script>
@endsection
