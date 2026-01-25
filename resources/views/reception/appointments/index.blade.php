@extends('layouts.dashboard')

@section('header', 'Appointments')

@section('content')
<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6 flex justify-between items-center border-b border-gray-200 bg-gray-50">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
            Appointments
        </h3>
        <div>
            <a href="{{ route('reception.patients.create') }}" class="mr-3 inline-flex items-center px-4 py-2 border border-blue-600 rounded-md font-semibold text-xs text-blue-600 uppercase tracking-widest hover:bg-blue-50 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-100 transition ease-in-out duration-150">
                + Register Patient
            </a>
            <a href="{{ route('reception.appointments.calendar') }}" class="mr-3 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:outline-none focus:shadow-outline-gray active:bg-gray-100 transition ease-in-out duration-150">
                📅 Calendar
            </a>
            <a href="{{ route('reception.appointments.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition ease-in-out duration-150">
                + New Appointment
            </a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            {{-- ... table content ... --}}
        </table>
    </div>
</div>

{{-- Invoice Creation Modal --}}
<div id="invoiceModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeInvoiceModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full sm:p-6" style="max-width: 800px;">
            
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Create Invoice</h3>
                <button onclick="closeInvoiceModal()" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form id="invoiceForm" action="" method="POST">
                @csrf
                
                {{-- Patient Name (Read Only) --}}
                <div class="mb-4 bg-gray-50 p-3 rounded-lg border border-gray-100">
                    <span class="text-sm text-gray-500 uppercase font-bold">Patient:</span>
                    <span id="modalPatientName" class="ml-2 font-medium text-gray-900"></span>
                </div>

                {{-- Items Table --}}
                <div class="border border-gray-200 rounded-lg overflow-hidden mb-4">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Item / Medicine</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase w-24">Price</th>
                                <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase w-20">Qty</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase w-24">Total</th>
                                <th class="px-4 py-2 w-10"></th>
                            </tr>
                        </thead>
                        <tbody id="invoiceItems" class="bg-white divide-y divide-gray-200">
                            {{-- Rows will be added here via JS --}}
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-right font-bold text-gray-700">Grand Total:</td>
                                <td class="px-4 py-3 text-right font-bold text-indigo-600" id="grandTotal">$0.00</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="flex gap-2 mb-6">
                    <button type="button" onclick="addServiceRow()" class="text-sm bg-gray-100 text-gray-700 hover:bg-gray-200 px-3 py-1.5 rounded-md flex items-center gap-1 transition">
                        <span>+ Add Service</span>
                    </button>
                    <button type="button" onclick="addMedicineRow()" class="text-sm bg-indigo-50 text-indigo-700 hover:bg-indigo-100 px-3 py-1.5 rounded-md flex items-center gap-1 transition">
                       <span>💊 Add Medicine</span>
                    </button>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeInvoiceModal()" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                        Generate Invoice
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
