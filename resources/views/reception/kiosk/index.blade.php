<!DOCTYPE html>
<html lang="en">
<head>
    <meta title="HealthFlow Kiosk">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Space Grotesk', sans-serif; }
    </style>
</head>
<body class="bg-gray-900 text-white h-screen overflow-hidden flex flex-col items-center justify-center relative">

    {{-- Background Animation --}}
    <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 z-0"></div>
    <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-indigo-900 via-gray-900 to-black z-0 pointer-events-none"></div>

    <div class="z-10 text-center w-full max-w-lg px-6" x-data="kioskApp()">
        
        {{-- Header --}}
        <div class="mb-12">
            <h1 class="text-4xl md:text-6xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-indigo-500 mb-4">Welcome</h1>
            <p class="text-gray-400 text-lg">Scan your Health ID to Check-in</p>
        </div>

        {{-- Simulated Scanner --}}
        <div class="relative w-64 h-64 mx-auto mb-12 group cursor-pointer" @click="simulateScan()">
            {{-- Scanning Frame --}}
            <div class="absolute inset-0 border-2 border-dashed border-indigo-500 rounded-xl opacity-50 group-hover:opacity-100 transition-opacity"></div>
            <div class="absolute inset-0 border-2 border-indigo-400 rounded-xl animate-pulse"></div>
            
            {{-- Scan Line --}}
            <div class="absolute top-0 left-0 w-full h-1 bg-green-400 shadow-[0_0_15px_rgba(74,222,128,0.7)] animate-scan"></div>
            
            <div class="flex items-center justify-center h-full">
                <svg class="w-16 h-16 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
            </div>
        </div>

        {{-- Input Manual --}}
        <div class="relative">
            <input type="number" x-model="patientId" @keydown.enter="checkIn()" placeholder="Or enter Patient ID..." 
                class="w-full bg-white/5 border border-white/10 rounded-full py-4 px-6 text-center text-xl tracking-widest focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all placeholder-gray-600">
            <button class="absolute top-2 right-2 bg-indigo-600 p-2 rounded-full hover:bg-indigo-500 transition" @click="checkIn()">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </div>

        {{-- Status Messages --}}
        <div class="mt-8 h-16">
            <template x-if="loading">
                <div class="flex items-center justify-center gap-2 text-indigo-300 animate-pulse">
                    <div class="w-2 h-2 bg-indigo-300 rounded-full"></div>
                    <div class="w-2 h-2 bg-indigo-300 rounded-full animation-delay-200"></div>
                    <div class="w-2 h-2 bg-indigo-300 rounded-full animation-delay-400"></div>
                    <span>Processing...</span>
                </div>
            </template>

            <template x-if="message">
                <div :class="success ? 'text-green-400' : 'text-red-400'" class="font-medium text-lg bg-black/30 p-4 rounded-lg backdrop-blur-sm border border-white/5 transition-all" x-text="message"></div>
            </template>
        </div>

    </div>

    {{-- Footer --}}
    <div class="absolute bottom-6 text-gray-600 text-xs">
        HealthFlow System Kiosk v1.0
    </div>

    <style>
        @keyframes scan {
            0% { top: 10%; opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { top: 90%; opacity: 0; }
        }
        .animate-scan {
            animation: scan 2s cubic-bezier(0.4, 0, 0.2, 1) infinite;
        }
        .animation-delay-200 { animation-delay: 0.2s; }
        .animation-delay-400 { animation-delay: 0.4s; }
    </style>

    <script>
        function kioskApp() {
            return {
                patientId: '',
                loading: false,
                message: null,
                success: false,

                // Simulate a QR scan (e.g., usually would come from a USB QR scanner acting as keyboard input)
                simulateScan() {
                    // Just prompt for demo
                    const id = prompt("Simulate Scan (Enter Patient ID):", "1");
                    if(id) {
                        this.patientId = id;
                        this.checkIn();
                    }
                },

                checkIn() {
                    if (!this.patientId) return;
                    
                    this.loading = true;
                    this.message = null;

                    fetch('{{ route('reception.kiosk.checkin') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ patient_id: this.patientId })
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.loading = false;
                        this.success = data.success;
                        this.message = data.message;
                        if(data.success) {
                            setTimeout(() => {
                                this.message = null;
                                this.patientId = '';
                            }, 5000);
                        }
                    })
                    .catch(e => {
                        this.loading = false;
                        this.success = false;
                        this.message = "System Error. Please try again.";
                    });
                }
            }
        }
    </script>
</body>
</html>
