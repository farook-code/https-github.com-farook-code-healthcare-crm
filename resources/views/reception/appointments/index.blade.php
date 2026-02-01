@extends('layouts.dashboard')

@section('header', 'Appointments')

@section('content')
<div class="bg-white dark:bg-slate-800 shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6 flex justify-between items-center border-b border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900">
        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
            {{ __('messages.appointments') }}
        </h3>
        <div>
            <a href="{{ route('reception.patients.create') }}" class="mr-3 inline-flex items-center px-4 py-2 border border-blue-600 rounded-md font-semibold text-xs text-blue-600 uppercase tracking-widest hover:bg-blue-50 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-100 transition ease-in-out duration-150">
                + {{ __('messages.register_patient') }}
            </a>
            <a href="{{ route('reception.appointments.calendar') }}" class="mr-3 inline-flex items-center px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-50 dark:hover:bg-slate-700 focus:outline-none focus:shadow-outline-gray active:bg-gray-100 transition ease-in-out duration-150">
                📅 {{ __('messages.calendar') }}
            </a>
            <a href="{{ route('reception.appointments.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition ease-in-out duration-150">
                + {{ __('messages.new_appointment') }}
            </a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700">
            <thead class="bg-gray-50 dark:bg-slate-700">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Patient
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Date & Time
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Doctor / Dept
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-blue-800 dark:text-blue-200 uppercase tracking-wider bg-blue-50 dark:bg-blue-900/20">
                        Next Step (Dr)
                    </th>
                    <th scope="col" class="relative px-6 py-3">
                        <span class="sr-only">Actions</span>
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-slate-800 divide-y divide-gray-200 dark:divide-slate-700">
                @forelse($appointments as $appointment)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $appointment->patient->name ?? 'Unknown' }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $appointment->patient->phone ?? '' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-white">{{ $appointment->appointment_date->format('M d, Y') }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-white">{{ $appointment->doctor->name ?? 'Not Assigned' }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $appointment->department->name ?? '' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $appointment->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                   ($appointment->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst($appointment->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap bg-blue-50 dark:bg-slate-800/50 border-l border-blue-100 dark:border-slate-700">
                            @php
                                $diagnosis = $appointment->diagnosis;
                                $recommendation = 'None';
                                
                                // Prioritize the dedicated column
                                if ($diagnosis && $diagnosis->follow_up_action) {
                                    $recommendation = $diagnosis->follow_up_action;
                                } 
                                // Fallback to parsing notes if column is empty
                                elseif ($diagnosis && preg_match('/\[RECOMMENDATION\]: (.*)/', $diagnosis->notes, $matches)) {
                                    $recommendation = trim($matches[1]);
                                }
                            @endphp

                            @if(str_contains($recommendation, 'Surgery'))
                                <a href="{{ route('admin.ot.bookings.create', ['patient_id' => $appointment->patient_id]) }}" class="px-2 py-1 text-xs font-bold rounded bg-red-100 text-red-800 border border-red-200 block text-center hover:bg-red-200 transition-colors">
                                    ⚡ OT / Surgery
                                </a>
                            @elseif(str_contains($recommendation, 'Admission'))
                                <a href="{{ route('admin.ipd.admissions.create', ['patient_id' => $appointment->patient_id]) }}" class="px-2 py-1 text-xs font-bold rounded bg-purple-100 text-purple-800 border border-purple-200 block text-center hover:bg-purple-200 transition-colors">
                                    🛏️ IPD Admission
                                </a>
                            @elseif(str_contains($recommendation, 'OPD'))
                                <a href="{{ route('reception.appointments.create', ['patient_id' => $appointment->patient_id]) }}" class="px-2 py-1 text-xs font-bold rounded bg-blue-100 text-blue-800 border border-blue-200 block text-center hover:bg-blue-200 transition-colors">
                                    📅 OPD Follow-up
                                </a>
                            @elseif($recommendation !== 'None')
                                <span class="px-2 py-1 text-xs font-medium rounded bg-gray-100 text-gray-800 block text-center">
                                    {{ $recommendation }}
                                </span>
                            @else
                                <span class="text-xs text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            @if($appointment->invoice)
                                <a href="{{ route('reception.invoices.print', $appointment->invoice) }}" target="_blank" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 font-bold mr-3 flex items-center justify-end gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                    Print Invoice
                                </a>
                            @else
                                <a href="javascript:void(0)" onclick="openInvoiceModal({{ $appointment->id }}, '{{ addslashes($appointment->patient->name ?? '') }}')" class="text-indigo-600 hover:text-indigo-900 mr-3">Generate Invoice</a>
                            @endif
                            
                            @if(str_contains($recommendation, 'OPD'))
                                <a href="{{ route('reception.appointments.create', ['patient_id' => $appointment->patient_id]) }}" class="text-blue-600 hover:text-blue-900 font-bold block mt-1">
                                    Book Follow-up &rarr;
                                </a>
                            @endif

                            @if(str_contains($recommendation, 'Admission'))
                                <a href="{{ route('admin.ipd.admissions.create', ['patient_id' => $appointment->patient_id]) }}" class="text-purple-600 hover:text-purple-900 font-bold block mt-1">
                                    Allocate Bed &rarr;
                                </a>
                            @endif
                             @if(str_contains($recommendation, 'Surgery'))
                                <a href="{{ route('admin.ot.bookings.create', ['patient_id' => $appointment->patient_id]) }}" class="text-red-600 hover:text-red-900 font-bold block mt-1">
                                    Book OT &rarr;
                                </a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                            No appointments found today.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Invoice Creation Modal --}}
<div id="invoiceModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeInvoiceModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full sm:p-6" style="max-width: 800px;">
            
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">{{ __('messages.create_invoice') }}</h3>
                <button onclick="closeInvoiceModal()" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form id="invoiceForm" action="" method="POST">
                @csrf
                
                {{-- Patient Name (Read Only) --}}
                <div class="mb-4 bg-gray-50 dark:bg-slate-700 p-3 rounded-lg border border-gray-100 dark:border-slate-600">
                    <span class="text-sm text-gray-500 dark:text-gray-300 uppercase font-bold">{{ __('messages.patient') }}:</span>
                    <span id="modalPatientName" class="ml-2 font-medium text-gray-900 dark:text-white"></span>
                </div>

                {{-- Items Table --}}
                <div class="border border-gray-200 dark:border-slate-700 rounded-lg overflow-hidden mb-4">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700">
                        <thead class="bg-gray-50 dark:bg-slate-700">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">{{ __('messages.item_medicine') }}</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase w-24">{{ __('messages.price') }}</th>
                                <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase w-20">{{ __('messages.quantity') }}</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase w-24">{{ __('messages.total') }}</th>
                                <th class="px-4 py-2 w-10"></th>
                            </tr>
                        </thead>
                        <tbody id="invoiceItems" class="bg-white dark:bg-slate-800 divide-y divide-gray-200 dark:divide-slate-700">
                            {{-- Rows will be added here via JS --}}
                        </tbody>
                        <tfoot class="bg-gray-50 dark:bg-slate-700">
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-right font-bold text-gray-700 dark:text-gray-200">{{ __('messages.grand_total') }}:</td>
                                <td class="px-4 py-3 text-right font-bold text-indigo-600 dark:text-indigo-400" id="grandTotal">$0.00</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="flex gap-2 mb-6">
                    <button type="button" onclick="addServiceRow()" class="text-sm bg-gray-100 text-gray-700 hover:bg-gray-200 px-3 py-1.5 rounded-md flex items-center gap-1 transition">
                        <span>+ {{ __('messages.add_service') }}</span>
                    </button>
                    <button type="button" onclick="addMedicineRow()" class="text-sm bg-indigo-50 text-indigo-700 hover:bg-indigo-100 px-3 py-1.5 rounded-md flex items-center gap-1 transition">
                       <span>💊 {{ __('messages.add_medicine') }}</span>
                    </button>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeInvoiceModal()" class="px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-slate-800 hover:bg-gray-50 dark:hover:bg-slate-700">
                        {{ __('messages.cancel') }}
                    </button>
                    <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                        {{ __('messages.generate_invoice') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let medicines = [];

    // Fetch medicines on load
    fetch('{{ route("admin.medicines.list") }}')
        .then(res => res.json())
        .then(data => medicines = data)
        .catch(console.error);

    function openInvoiceModal(appointmentId, patientName) {
        const form = document.getElementById('invoiceForm');
        form.action = `/reception/appointments/${appointmentId}/invoice`; // Ensure this matches Route::post
        document.getElementById('modalPatientName').textContent = patientName;
        document.getElementById('invoiceItems').innerHTML = '';
        document.getElementById('grandTotal').textContent = '$0.00';
        
        // Add default Consultation Fee
        addServiceRow('Consultation Fee', 50);
        
        document.getElementById('invoiceModal').classList.remove('hidden');
    }

    function closeInvoiceModal() {
        document.getElementById('invoiceModal').classList.add('hidden');
    }

    function calculateTotal() {
        let total = 0;
        document.querySelectorAll('.item-row').forEach(row => {
            const price = parseFloat(row.querySelector('.price-input').value) || 0;
            const qty = parseInt(row.querySelector('.qty-input').value) || 1;
            const rowTotal = price * qty;
            row.querySelector('.row-total').textContent = '$' + rowTotal.toFixed(2);
            row.querySelector('.hidden-total').value = rowTotal.toFixed(2);
            total += rowTotal;
        });
        document.getElementById('grandTotal').textContent = '$' + total.toFixed(2);
    }

    function removeRow(btn) {
        btn.closest('tr').remove();
        calculateTotal();
    }

    function addServiceRow(name = '', price = '') {
        const tbody = document.getElementById('invoiceItems');
        const tr = document.createElement('tr');
        tr.className = 'item-row';
        tr.innerHTML = `
            <td class="px-4 py-2">
                <input type="text" name="descriptions[]" value="${name}" class="block w-full border-gray-300 rounded-md text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Description" required>
                <input type="hidden" name="types[]" value="service">
                <input type="hidden" name="medicine_ids[]" value="">
            </td>
            <td class="px-4 py-2">
                <input type="number" name="prices[]" value="${price}" step="0.01" class="price-input block w-full border-gray-300 rounded-md text-sm text-right focus:border-indigo-500 focus:ring-indigo-500" oninput="calculateTotal()" required>
            </td>
            <td class="px-4 py-2">
                <input type="number" name="quantities[]" value="1" min="1" class="qty-input block w-full border-gray-300 rounded-md text-sm text-center focus:border-indigo-500 focus:ring-indigo-500" oninput="calculateTotal()" required>
            </td>
            <td class="px-4 py-2 text-right text-sm font-medium text-gray-900 row-total">
                $${Number(price).toFixed(2)}
            </td>
            <input type="hidden" name="totals[]" class="hidden-total" value="${Number(price).toFixed(2)}">
            <td class="px-4 py-2 text-center">
                <button type="button" onclick="removeRow(this)" class="text-red-500 hover:text-red-700">&times;</button>
            </td>
        `;
        tbody.appendChild(tr);
        calculateTotal();
    }

    function addMedicineRow() {
        const tbody = document.getElementById('invoiceItems');
        const tr = document.createElement('tr');
        tr.className = 'item-row';
        
        let options = '<option value="">Select Medicine...</option>';
        medicines.forEach(m => {
            options += `<option value="${m.id}" data-price="${m.price}" data-name="${m.name}">${m.name} ($${m.price}) - Stock: ${m.stock_quantity}</option>`;
        });

        tr.innerHTML = `
            <td class="px-4 py-2">
                <select class="block w-full border-gray-300 rounded-md text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" onchange="updateMedicinePrice(this)" required>
                    ${options}
                </select>
                <input type="hidden" name="descriptions[]" class="description-input">
                <input type="hidden" name="types[]" value="medicine">
                <input type="hidden" name="medicine_ids[]" class="medicine-id-input">
            </td>
            <td class="px-4 py-2">
                <input type="number" name="prices[]" step="0.01" class="price-input block w-full border-gray-300 rounded-md text-sm text-right bg-gray-50" readonly>
            </td>
            <td class="px-4 py-2">
                <input type="number" name="quantities[]" value="1" min="1" class="qty-input block w-full border-gray-300 rounded-md text-sm text-center focus:border-indigo-500 focus:ring-indigo-500" oninput="calculateTotal()" required>
            </td>
            <td class="px-4 py-2 text-right text-sm font-medium text-gray-900 row-total">$0.00</td>
            <input type="hidden" name="totals[]" class="hidden-total">
            <td class="px-4 py-2 text-center">
                <button type="button" onclick="removeRow(this)" class="text-red-500 hover:text-red-700">&times;</button>
            </td>
        `;
        tbody.appendChild(tr);
    }

    function updateMedicinePrice(select) {
        const row = select.closest('tr');
        const option = select.options[select.selectedIndex];
        const price = option.getAttribute('data-price') || 0;
        const name = option.getAttribute('data-name') || '';
        
        row.querySelector('.price-input').value = price;
        row.querySelector('.medicine-id-input').value = select.value;
        row.querySelector('.description-input').value = name;
        
        calculateTotal();
    }
</script>
</div>
@endsection
