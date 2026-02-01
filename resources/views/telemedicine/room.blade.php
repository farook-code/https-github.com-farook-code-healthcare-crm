@extends('layouts.dashboard')

@section('header', 'Telemedicine Room')

@section('content')
<div class="h-[calc(100vh-8rem)]">
    
    <div x-data="telemedicine()" class="h-full">
        
        {{-- Consent Modal --}}
        <div x-show="!joined" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/90 backdrop-blur-sm" x-cloak>
            <div class="bg-white rounded-xl shadow-2xl max-w-lg w-full p-8 mx-4 border border-slate-200">
                <div class="text-center mb-6">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 text-blue-600 mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                    </div>
                    <h2 class="text-2xl font-bold text-slate-900">Telemedicine Consent</h2>
                    <p class="text-slate-500 mt-2">Before joining the call, please review and accept the terms.</p>
                </div>

                <div class="bg-slate-50 p-4 rounded-lg text-sm text-slate-600 mb-6 h-48 overflow-y-auto border border-slate-200 leading-relaxed">
                    <p class="font-bold mb-2">Informed Consent for Telemedicine Services</p>
                    <p class="mb-2">1. I understand that telemedicine involves the use of electronic communications to enable healthcare providers at different locations to share individual patient medical information for the purpose of improving patient care.</p>
                    <p class="mb-2">2. I understand that there are potential risks to this technology, including interruptions, unauthorized access, and technical difficulties.</p>
                    <p class="mb-2">3. I have the right to withhold or withdraw consent at any time without affecting my right to future care or treatment.</p>
                    <p class="mb-2">4. I agree that my medical data may be transmitted electronically.</p>
                    <p>By clicking "I Agree", I certify that I have read and understand this agreement.</p>
                </div>

                <div class="space-y-4">
                    <label class="flex items-center gap-3 p-3 border border-slate-200 rounded-lg hover:bg-slate-50 cursor-pointer transition">
                        <input type="checkbox" x-model="consented" class="w-5 h-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                        <span class="text-sm font-medium text-slate-700">I have read and agree to the Telemedicine Consent Form.</span>
                    </label>
                    
                    <button @click="initCall()" 
                            :disabled="!consented" 
                            :class="!consented ? 'opacity-50 cursor-not-allowed bg-slate-400' : 'bg-blue-600 hover:bg-blue-700 shadow-lg shadow-blue-200'"
                            class="w-full py-3.5 text-white font-bold rounded-lg transition transform active:scale-95 text-lg">
                        Join Consultation Room
                    </button>
                    
                    <a href="{{ url()->previous() }}" class="block text-center text-sm text-slate-400 hover:text-slate-600 p-2">Cancel and Return</a>
                </div>
            </div>
        </div>

        {{-- Room Container --}}
        <div x-show="joined" class="h-full flex flex-col bg-slate-900 rounded-xl overflow-hidden shadow-2xl relative" x-cloak>
            <div id="meet" class="flex-1 w-full h-full"></div>
            
            {{-- Overlay Info --}}
            <div class="absolute top-4 left-4 z-10 bg-black/60 backdrop-blur-md text-white px-4 py-2 rounded-lg border border-white/10 pointer-events-none">
                <h2 class="text-sm font-bold flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
                    Live Consultation
                </h2>
                <p class="text-xs text-slate-300 mt-0.5">Patient: {{ $appointment->patient->name }}</p>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src='https://meet.jit.si/external_api.js'></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('telemedicine', () => ({
            consented: false,
            joined: false,
            
            initCall() {
               this.joined = true;
               
               // Small delay to ensure container is visible
               setTimeout(() => {
                   const domain = 'meet.jit.si';
                   const options = {
                       roomName: '{{ $roomName }}',
                       width: '100%',
                       height: '100%',
                       parentNode: document.querySelector('#meet'),
                       userInfo: {
                           displayName: '{{ $userName }}'
                       },
                       configOverwrite: { 
                           startWithAudioMuted: false, 
                           startWithVideoMuted: false,
                           prejoinPageEnabled: false 
                       },
                       interfaceConfigOverwrite: {
                            TOOLBAR_BUTTONS: [
                                'microphone', 'camera', 'closedcaptions', 'desktop', 'fullscreen',
                                'fodeviceselection', 'hangup', 'profile', 'chat', 'recording',
                                'livestreaming', 'etherpad', 'sharedvideo', 'settings', 'raisehand',
                                'videoquality', 'filmstrip', 'invite', 'feedback', 'stats', 'shortcuts',
                                'tileview', 'videobackgroundblur', 'download', 'help', 'mute-everyone',
                                'security'
                            ]
                       }
                   };
                   const api = new JitsiMeetExternalAPI(domain, options);
                   
                   api.addEventListeners({
                       videoConferenceLeft: function () {
                           // Return to appointment page on hangup
                           window.location.href = "{{ url()->previous() }}";
                       }
                   });
               }, 100);
            }
        }))
    })
</script>
@endpush
