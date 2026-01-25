@extends('layouts.dashboard')

@section('header', 'Waitlist Management')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6" x-data="{ openModal: false }">

    {{-- Actions --}}
    <div class="flex justify-end mb-6">
        <button @click="openModal = true" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 font-bold text-sm flex items-center shadow-sm transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add to Waitlist
        </button>
    </div>

    {{-- Waitlist Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Patient</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Department / Doctor</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Preferred Date</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Notes</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-200">
                @forelse($waitlist as $item)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-slate-900">{{ $item->patient->name }}</div>
                            <div class="text-xs text-slate-500">{{ $item->patient->phone }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-slate-900">{{ $item->department->name ?? 'Any' }}</div>
                            @if($item->doctor)
                                <div class="text-xs text-indigo-600">Dr. {{ $item->doctor->name }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">
                            {{ $item->preferred_date ? $item->preferred_date->format('M d, Y') : 'ASAP' }}
                        </td>
                         <td class="px-6 py-4 whitespace-normal text-sm text-slate-500 max-w-xs">
                            {{ $item->notes ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex items-center gap-2">
                            {{-- Convert (Book) --}}
                            <a href="{{ route('reception.appointments.create', ['patient_id' => $item->patient_id, 'doctor_id' => $item->doctor_id]) }}" class="text-emerald-600 hover:text-emerald-900 font-bold bg-emerald-50 px-2 py-1 rounded border border-emerald-100">
                                Book
                            </a>
                            
                            {{-- Mark Cancelled --}}
                            <form action="{{ route('reception.waitlist.update', $item) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="cancelled">
                                <button type="submit" class="text-slate-400 hover:text-red-600 font-bold px-2" onclick="return confirm('Cancel this waitlist entry?')">
                                    Cancel
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                            Waitlist is empty.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $waitlist->links() }}
        </div>
    </div>

    {{-- Add Modal --}}
    <div x-show="openModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4">
             <div class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity" @click="openModal = false"></div>

             <div class="bg-white rounded-xl shadow-xl transform transition-all sm:max-w-lg w-full p-6 relative z-10">
                 <h3 class="text-lg font-bold text-slate-900 mb-4">Add Patient to Waitlist</h3>
                 
                 <form action="{{ route('reception.waitlist.store') }}" method="POST">
                     @csrf
                     <div class="space-y-4">
                         <div>
                             <label class="block text-sm font-bold text-slate-700 mb-1">Patient</label>
                             <select name="patient_id" required class="w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                 <option value="">Select Patient</option>
                                 @foreach($patients as $p)
                                     <option value="{{ $p->id }}">{{ $p->first_name }} {{ $p->last_name }}</option>
                                 @endforeach
                             </select>
                         </div>

                         <div>
                             <label class="block text-sm font-bold text-slate-700 mb-1">Department (Optional)</label>
                             <select name="department_id" class="w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                 <option value="">Any Department</option>
                                 @foreach($departments as $d)
                                     <option value="{{ $d->id }}">{{ $d->name }}</option>
                                 @endforeach
                             </select>
                         </div>

                          <div>
                             <label class="block text-sm font-bold text-slate-700 mb-1">Preferred Doctor (Optional)</label>
                             <select name="doctor_id" class="w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                 <option value="">Any Doctor</option>
                                 @foreach($doctors as $doc)
                                     <option value="{{ $doc->id }}">Dr. {{ $doc->name }}</option>
                                 @endforeach
                             </select>
                         </div>

                         <div>
                             <label class="block text-sm font-bold text-slate-700 mb-1">Preferred Date (Optional)</label>
                             <input type="date" name="preferred_date" min="{{ date('Y-m-d') }}" class="w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                         </div>

                         <div>
                             <label class="block text-sm font-bold text-slate-700 mb-1">Notes</label>
                             <textarea name="notes" rows="3" class="w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"></textarea>
                         </div>
                     </div>

                     <div class="mt-6 flex justify-end gap-3">
                         <button type="button" @click="openModal = false" class="px-4 py-2 text-slate-700 hover:bg-slate-100 rounded-lg font-bold">Cancel</button>
                         <button type="submit" class="px-4 py-2 bg-indigo-600 text-white hover:bg-indigo-700 rounded-lg font-bold">Add to Waitlist</button>
                     </div>
                 </form>
             </div>
        </div>
    </div>

</div>
@endsection
