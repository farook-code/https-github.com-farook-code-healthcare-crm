@extends('layouts.dashboard')

@section('header', __('messages.patient_flow_board'))

@section('content')
<div class="h-full flex flex-col" x-data="kanban()">
    <div class="flex-1 overflow-x-auto overflow-y-hidden whitespace-nowrap pb-4">
        
        {{-- Columns --}}
        @foreach($columns as $status => $title)
            <div class="inline-block w-80 h-full align-top mr-4 bg-gray-100 rounded-xl shadow-sm border border-gray-200 flex flex-col">
                {{-- Column Header --}}
                <div class="p-4 border-b border-gray-200 flex justify-between items-center bg-white rounded-t-xl">
                    <h3 class="font-bold text-gray-700">{{ $title }}</h3>
                    <span class="bg-gray-200 text-gray-600 text-xs font-bold px-2 py-1 rounded-full" x-text="counts['{{ $status }}']">0</span>
                </div>

                {{-- Draggable Area --}}
                <div class="p-3 flex-1 overflow-y-auto space-y-3 min-h-[500px]" 
                     @dragover.prevent 
                     @drop.prevent="drop($event, '{{ $status }}')">
                    
                    @foreach($appointments->where('status', $status) as $appt)
                         <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 cursor-move hover:shadow-md transition-shadow group relative"
                              draggable="true" 
                              @dragstart="dragStart($event, {{ $appt->id }})">
                            
                            {{-- Card Content --}}
                            <div class="flex justify-between items-start mb-2">
                                <span class="font-bold text-gray-900 text-sm">{{ $appt->patient->name }}</span>
                                <span class="text-xs text-gray-400">{{ $appt->appointment_time }}</span>
                            </div>
                            
                            <div class="text-xs text-gray-500 mb-3">
                                <p>Dr. {{ $appt->doctor->name }}</p>
                                <p class="truncate">{{ $appt->department->name }}</p>
                            </div>

                            <div class="flex justify-between items-center mt-2">
                                <span class="text-[10px] font-semibold px-2 py-0.5 rounded bg-blue-50 text-blue-600">
                                    {{ $appt->type ?? __('messages.consultation_type') }}
                                </span>
                                <a href="{{ route('doctor.appointments.show', $appt->id) }}" class="text-gray-400 hover:text-indigo-600 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                </a>
                            </div>
                         </div>
                    @endforeach

                </div>
            </div>
        @endforeach

    </div>
</div>

<script>
    function kanban() {
        return {
            counts: {
                'scheduled': {{ $appointments->where('status', 'scheduled')->count() }},
                'checked_in': {{ $appointments->where('status', 'checked_in')->count() }},
                'vitals_done': {{ $appointments->where('status', 'vitals_done')->count() }},
                'in_consultation': {{ $appointments->where('status', 'in_consultation')->count() }},
                'pharmacy': {{ $appointments->where('status', 'pharmacy')->count() }},
                'completed': {{ $appointments->where('status', 'completed')->count() }},
            },
            draggedId: null,

            dragStart(event, id) {
                this.draggedId = id;
                event.dataTransfer.effectAllowed = 'move';
                event.target.classList.add('opacity-50');
            },

            drop(event, newStatus) {
                const id = this.draggedId;
                if (!id) return;

                // Optimistic UI Update (Reload page for now is safer until full JS re-render logic added)
                // Sending Request
                fetch('{{ route('nurse.flow.update') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ id: id, status: newStatus })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload(); // Simple refresh to sync state
                    } else {
                        alert('{{ __('messages.error_moving_patient') }}');
                    }
                });
            }
        }
    }
</script>
@endsection
