@extends('layouts.dashboard')

@section('header', 'Telemedicine Room')

@section('content')
<div class="h-[calc(100vh-8rem)] flex flex-col bg-slate-900 rounded-xl overflow-hidden shadow-2xl relative">
    
    {{-- Jitsi Container --}}
    <div id="meet" class="flex-1 w-full h-full"></div>

    {{-- Overlay Info --}}
    <div class="absolute top-4 left-4 z-10 bg-black/60 backdrop-blur-md text-white px-4 py-2 rounded-lg border border-white/10">
        <h2 class="text-sm font-bold flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
            Live Consultation
        </h2>
        <p class="text-xs text-slate-300 mt-0.5">Patient: {{ $appointment->patient->name }}</p>
    </div>

</div>

<script src='https://meet.jit.si/external_api.js'></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
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
                prejoinPageEnabled: false // Skip prejoin for seamless feel
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
            },
        };
        const api = new JitsiMeetExternalAPI(domain, options);
        
        // Handle Hangup
        api.addEventListeners({
            videoConferenceLeft: function () {
                window.close(); // Or redirect back to appointment
                window.location.href = "{{ url()->previous() }}";
            }
        });
    });
</script>
@endsection
