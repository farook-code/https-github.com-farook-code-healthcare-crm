@extends('layouts.dashboard')

@section('header', __('messages.create_invoice'))

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            
            <form id="invoiceForm" method="POST" action="{{ route('reception.invoices.store') }}">
                @csrf
                
                @php
                    $role = auth()->user()->role->slug ?? '';
                    $isFixed = in_array($role, ['pharmacist', 'lab_technician']);
                @endphp

                @if($isFixed)
                    <div class="mb-6 border-b border-gray-200 pb-4">
                        <h2 class="text-2xl font-bold text-gray-800">
                            {{ $role === 'pharmacist' ? 'Pharmacy Invoice' : 'Lab Test Invoice' }}
                        </h2>
                    </div>
                @endif

                {{-- Category Selection --}}
                <div class="mb-6 border-b border-gray-200 pb-6 {{ $isFixed ? 'hidden' : '' }}">
                    <label class="block text-lg font-medium text-gray-900 mb-4">Billing Category</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <label class="cursor-pointer">
                            <input type="radio" name="category" value="opd" class="peer sr-only" onchange="toggleCategory('opd')" @checked( (!request()->has('category') && !$isFixed) || request('category') == 'opd' )>
                            <div class="p-4 border rounded-lg text-center hover:bg-blue-50 peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 transition-all">
                                <span class="font-bold block">OPD</span>
                                <span class="text-xs">Consultations</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="category" value="pharmacy" class="peer sr-only" onchange="toggleCategory('pharmacy')" @checked( request('category') == 'pharmacy' || $role == 'pharmacist' )>
                            <div class="p-4 border rounded-lg text-center hover:bg-green-50 peer-checked:bg-green-600 peer-checked:text-white peer-checked:border-green-600 transition-all">
                                <span class="font-bold block">Pharmacy</span>
                                <span class="text-xs">Medicines Only</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="category" value="ipd" class="peer sr-only" onchange="toggleCategory('ipd')" @checked( request('category') == 'ipd' )>
                            <div class="p-4 border rounded-lg text-center hover:bg-purple-50 peer-checked:bg-purple-600 peer-checked:text-white peer-checked:border-purple-600 transition-all">
                                <span class="font-bold block">IPD</span>
                                <span class="text-xs">Admissions</span>
                            </div>
                        </label>
                         <label class="cursor-pointer">
                            <input type="radio" name="category" value="lab" class="peer sr-only" onchange="toggleCategory('lab')" @checked( request('category') == 'lab' || $role == 'lab_technician' )>
                            <div class="p-4 border rounded-lg text-center hover:bg-orange-50 peer-checked:bg-orange-600 peer-checked:text-white peer-checked:border-orange-600 transition-all">
                                <span class="font-bold block">Lab / Tests</span>
                                <span class="text-xs">Diagnostics</span>
                            </div>
                        </label>
                    </div>  
                </div>

                <div class="grid grid-cols-1 gap-6 mb-8">
                    
                    {{-- Section: OPD (Appointment) --}}
                    <div id="section-opd" class="category-section">
                        <label for="appointment_id" class="block text-sm font-medium text-gray-700 mb-2">Select Valid Appointment</label>
                        <select id="appointment_id" name="appointment_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" onchange="loadAppointmentDetails(this.value)">
                            <option value="">-- Choose Appointment --</option>
                            @foreach($appointments as $appt)
                                <option value="{{ $appt->id }}" {{ (isset($selectedAppointment) && $selectedAppointment->id == $appt->id) ? 'selected' : '' }}>
                                    {{ $appt->patient->name ?? 'Unknown Patient' }} - {{ $appt->doctor->name ?? 'Unknown Doctor' }} ({{ $appt->appointment_date->format('M d, H:i') }})
                                </option>
                            @endforeach
                        </select>
                         <div id="opd-details" class="mt-3 text-sm text-gray-500 hidden bg-gray-50 p-3 rounded"></div>
                    </div>

                    {{-- Section: IPD (Admission) --}}
                    <div id="section-ipd" class="category-section hidden">
                        <label for="ipd_admission_id" class="block text-sm font-medium text-gray-700 mb-2">Select Active Admission</label>
                        <select id="ipd_admission_id" name="ipd_admission_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" onchange="setPatientFromAttribute(this)">
                            <option value="">-- Choose Admitted Patient --</option>
                            @foreach($ipdAdmissions as $adm)
                                <option value="{{ $adm->id }}" 
                                    data-patient-id="{{ $adm->patient_id }}"
                                    data-bed-charge="{{ $adm->bed->daily_charge ?? 0 }}"
                                    data-bed-name="{{ $adm->bed->bed_number ?? 'Bed' }}">
                                    {{ $adm->patient->name }} (Bed: {{ $adm->bed->bed_number ?? 'N/A' }}) - Admitted: {{ $adm->admission_date->format('M d') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Section: Patient (Pharmacy/Lab) --}}
                    <div id="section-patient" class="category-section hidden">
                        <label for="patient_id" class="block text-sm font-medium text-gray-700 mb-2">Select Patient</label>
                        <select id="patient_id" name="patient_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">-- Search Patient --</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}">{{ $patient->name }} ({{ $patient->patient_code }})</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Hidden Patient ID for OPD/IPD Auto-fill --}}
                     <input type="hidden" name="auto_patient_id" id="auto_patient_id">

                </div>

                {{-- Invoice Items --}}
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Billing Items</h3>
                    
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description / Medicine</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Unit Price</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Qty</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Total</th>
                                    <th class="px-6 py-3 w-16"></th>
                                </tr>
                            </thead>
                            <tbody id="invoiceItems" class="bg-white divide-y divide-gray-200">
                                {{-- JS will populate matches --}}
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-right font-bold text-gray-700">Grand Total:</td>
                                    <td class="px-6 py-4 text-right font-bold text-indigo-600 text-lg" id="grandTotal">$0.00</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="mt-4 flex gap-3">
                        <button type="button" onclick="addServiceRow()" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            + Add Item
                        </button>
                        <button type="button" id="addMedicineBtn" onclick="addMedicineRow()" class="inline-flex items-center px-3 py-2 border border-transparent shadow-sm text-sm leading-4 font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200">
                            💊 Add Medicine
                        </button>
                        <button type="button" id="addLabTestBtn" onclick="addLabTestRow()" class="hidden inline-flex items-center px-3 py-2 border border-transparent shadow-sm text-sm leading-4 font-medium rounded-md text-orange-700 bg-orange-100 hover:bg-orange-200 ml-2">
                            🧪 Add Lab Test
                        </button>
                    </div>
                </div>

                <div class="flex justify-end pt-5 border-t border-gray-200">
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" id="generateBtn">
                        Generate Invoice
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let medicines = @json($medicines);
    let labTests = @json($labTests ?? []);

    function toggleCategory(cat) {
        // Hide all sections
        document.querySelectorAll('.category-section').forEach(el => el.classList.add('hidden'));
        
        // Show selected section
        if (cat === 'opd') {
            document.getElementById('section-opd').classList.remove('hidden');
            // Enable/Disable inputs to prevent validation errors for hidden fields? 
            // Better to rely on server side check or smart update
        } else if (cat === 'ipd') {
            document.getElementById('section-ipd').classList.remove('hidden');
        } else {
            document.getElementById('section-patient').classList.remove('hidden');
        }

        // Reset items if switching? Maybe keep them.
        
        // Button Logic
        const medBtn = document.getElementById('addMedicineBtn');
        const labBtn = document.getElementById('addLabTestBtn');
        if(medBtn) medBtn.classList.add('hidden');
        if(labBtn) labBtn.classList.add('hidden');

        if (cat === 'pharmacy' && medBtn) medBtn.classList.remove('hidden');
        if (cat === 'lab' && labBtn) labBtn.classList.remove('hidden');
    }

    // Initialize correct category on load
    document.addEventListener('DOMContentLoaded', function() {
        const checkedCategory = document.querySelector('input[name="category"]:checked');
        if(checkedCategory) {
            toggleCategory(checkedCategory.value);
        }
    });

    // OPD Logic
    function loadAppointmentDetails(id) {
        if (!id) return;
        fetch(`/reception/appointments/${id}/details`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('opd-details').classList.remove('hidden');
                document.getElementById('opd-details').innerHTML = `<strong>Patient:</strong> ${data.patient_name} <br> <strong>Doctor:</strong> ${data.doctor_name}`;
                
                // Clear existing OPD Fees
                const tbody = document.getElementById('invoiceItems');
                tbody.querySelectorAll('tr').forEach(row => {
                     const descInput = row.querySelector("input[name='descriptions[]']");
                     if(descInput && (descInput.value.includes('Consultation Fee') || descInput.closest('tr').dataset.source === 'prescription')) {
                         row.remove();
                     }
                });
                
                // Auto-Add Consultation Fee
                if(data.consultation_fee) {
                    addServiceRow(`Consultation Fee (Dr. ${data.doctor_name})`, data.consultation_fee);
                }

                // Auto-Add Prescriptions linked to this appointment
                if(data.prescriptions && data.prescriptions.length > 0) {
                     data.prescriptions.forEach(med => {
                         addMedicineRowCompat(med); // Helper to add medicine row
                     });
                }
                updateGrandTotal();
            });
    }

    // Patient Logic (Pharmacy/Lab)
    // Wait for DOM
    document.addEventListener('DOMContentLoaded', function() {
        const pSelect = document.getElementById('patient_id');
        if(pSelect) {
            pSelect.addEventListener('change', function() {
                const patientId = this.value;
                if(!patientId) return;

                // Only fetch if category is NOT OPD/IPD (avoids conflict with setPatientFromAttribute logic which also sets this)
                // Actually, if we are in Pharmacy mode, we want this.
                // We can check current category
                const activeCategory = document.querySelector("input[name='category']:checked");
                if(activeCategory && (activeCategory.value === 'opd' || activeCategory.value === 'ipd')) return;

                fetch(`/reception/patients/${patientId}/pending-items`)
                    .then(res => res.json())
                    .then(data => {
                         // Clear previous "prescription" source rows first
                         const tbody = document.getElementById('invoiceItems');
                         tbody.querySelectorAll('tr').forEach(row => {
                             if(row.dataset.source === 'prescription') row.remove();
                         });

                         if(data.prescriptions && data.prescriptions.length > 0) {
                             data.prescriptions.forEach(med => {
                                 addMedicineRowCompat(med);
                             });
                             updateGrandTotal();
                         }
                    });
            });
        }
    });

    // Helper for adding medicine rows dynamically
    function addMedicineRowCompat(med) {
        const tbody = document.getElementById('invoiceItems');
        const tr = document.createElement('tr');
        tr.dataset.source = 'prescription'; // Mark as auto-added

        let options = `<option value="">Select Medicine...</option>`;
        let selectedValue = '';
        let foundMatch = false;

        // Ensure 'medicines' variable is available (passed from controller: $medicines)
        // If JS variable 'medicines' is undefined, this crashes.
        // It is defined at top of script section in create.blade.php usually?
        // Let's assume it is. If not, we fallback to text input?
        // Assuming it is there.
        
        if(typeof medicines !== 'undefined') {
            medicines.forEach(m => { 
                 const isMatch = (med.medicine_id && m.id == med.medicine_id) || (m.name.toLowerCase() === med.name.toLowerCase());
                 if(isMatch) {
                     selectedAttr = 'selected';
                     selectedValue = m.id;
                     foundMatch = true;
                 } else {
                     selectedAttr = '';
                 }
                 // Avoid re-selecting if found match already
                 if(foundMatch && !isMatch) selectedAttr = '';
                 
                 options += `<option value="${m.id}" data-price="${m.price}" data-name="${m.name}" ${selectedAttr}>${m.name} ($${m.price})</option>`; 
            });
        } else {
            console.error('Medicines list not loaded');
            return;
        }
        
        // If we found a match, price is from inventory. If not, price is 0 or passed med.price?
        // med.price comes from controller logic (inventory price).
        const price = med.price || 0;

        tr.innerHTML = `
            <td class="px-6 py-4">
                <select class="block w-full border-gray-300 rounded-md shadow-sm sm:text-sm" onchange="updateMedicineRow(this)" name="medicine_ids[]" required>
                    ${options}
                </select>
                <input type="hidden" name="descriptions[]" class="desc-hidden" value="${med.name}">
                <input type="hidden" name="types[]" value="medicine">
            </td>
            <td class="px-6 py-4"><input type="number" name="prices[]" value="${price}" step="0.01" class="price-input block w-full bg-gray-50 border-gray-300 rounded-md sm:text-sm text-right" readonly></td>
            <td class="px-6 py-4"><input type="number" name="quantities[]" value="${med.dosage ? 1 : 1}" min="1" class="qty-input block w-full border-gray-300 rounded-md sm:text-sm text-center" oninput="calculateRowTotal(this)" required></td>
            <td class="px-6 py-4 text-right text-sm font-medium text-gray-900 row-total">$${(price * 1).toFixed(2)}</td>
            <td class="px-6 py-4 text-center"><button type="button" onclick="removeRow(this)" class="text-red-600 hover:text-red-900">x</button></td>
        `;
        tbody.appendChild(tr);
    }

    function setPatientFromAttribute(selectUrl) {
        const option = selectUrl.options[selectUrl.selectedIndex];
        
        // Auto-Fill Patient
        if(option.dataset.patientId) {
             const patientSelect = document.getElementById('patient_id');
             patientSelect.value = option.dataset.patientId;
        }

        // Clear existing Room Charges to avoid duplicates
        const tbody = document.getElementById('invoiceItems');
        tbody.querySelectorAll('tr').forEach(row => {
             const descInput = row.querySelector("input[name='descriptions[]']");
             if(descInput && descInput.value.includes('Room Charge')) {
                 row.remove();
             }
        });
        updateGrandTotal();

        // Auto-Add Bed Charge Row
        if(option.dataset.bedCharge) {
            const charge = parseFloat(option.dataset.bedCharge);
            addServiceRow(`Room Charge (${option.dataset.bedName})`, charge);
        }
    }

    // Generic Item Logic (Same as before but simplified)
    function addServiceRow(name = '', price = 0) {
        const tbody = document.getElementById('invoiceItems');
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td class="px-6 py-4"><input type="text" name="descriptions[]" value="${name}" class="block w-full border-gray-300 rounded-md shadow-sm sm:text-sm" placeholder="Service Name" required>
            <input type="hidden" name="types[]" value="service"><input type="hidden" name="medicine_ids[]" value=""></td>
            <td class="px-6 py-4"><input type="number" name="prices[]" value="${price}" step="0.01" class="price-input block w-full border-gray-300 rounded-md sm:text-sm text-right" oninput="calculateRowTotal(this)" required></td>
            <td class="px-6 py-4"><input type="number" name="quantities[]" value="1" min="1" class="qty-input block w-full border-gray-300 rounded-md sm:text-sm text-center" oninput="calculateRowTotal(this)" required></td>
            <td class="px-6 py-4 text-right text-sm font-medium text-gray-900 row-total">$${parseFloat(price).toFixed(2)}</td>
            <td class="px-6 py-4 text-center"><button type="button" onclick="removeRow(this)" class="text-red-600 hover:text-red-900">x</button></td>
        `;
        tbody.appendChild(tr);
        updateGrandTotal();
    }

    function addMedicineRow() {
        const tbody = document.getElementById('invoiceItems');
        const tr = document.createElement('tr');
        let options = `<option value="">Select Medicine...</option>`;
        medicines.forEach(med => { options += `<option value="${med.id}" data-price="${med.price}" data-name="${med.name}">${med.name} ($${med.price})</option>`; });

        tr.innerHTML = `
            <td class="px-6 py-4">
                <select class="block w-full border-gray-300 rounded-md shadow-sm sm:text-sm" onchange="updateMedicineRow(this)" required>${options}</select>
                <input type="hidden" name="descriptions[]" class="desc-hidden">
                <input type="hidden" name="types[]" value="medicine">
                <input type="hidden" name="medicine_ids[]" class="med-id-hidden">
            </td>
            <td class="px-6 py-4"><input type="number" name="prices[]" value="0.00" step="0.01" class="price-input block w-full bg-gray-50 border-gray-300 rounded-md sm:text-sm text-right" readonly></td>
            <td class="px-6 py-4"><input type="number" name="quantities[]" value="1" min="1" class="qty-input block w-full border-gray-300 rounded-md sm:text-sm text-center" oninput="calculateRowTotal(this)" required></td>
            <td class="px-6 py-4 text-right text-sm font-medium text-gray-900 row-total">$0.00</td>
            <td class="px-6 py-4 text-center"><button type="button" onclick="removeRow(this)" class="text-red-600 hover:text-red-900">x</button></td>
        `;
        tbody.appendChild(tr);
    }

    function updateMedicineRow(select) {
        const row = select.closest('tr');
        const option = select.options[select.selectedIndex];
        if (option.value) {
            row.querySelector('.price-input').value = option.dataset.price;
            row.querySelector('.desc-hidden').value = option.dataset.name;
            row.querySelector('.med-id-hidden').value = option.value;
            calculateRowTotal(select);
        }
    }

    function addLabTestRow() {
        const tbody = document.getElementById('invoiceItems');
        const tr = document.createElement('tr');
        let options = `<option value="">Select Lab Test...</option>`;
        labTests.forEach(test => { 
            options += `<option value="${test.code}" data-price="${test.price}" data-name="${test.name}">${test.name} ($${test.price})</option>`; 
        });

        tr.innerHTML = `
            <td class="px-6 py-4">
                <select class="block w-full border-gray-300 rounded-md shadow-sm sm:text-sm" onchange="updateLabTestRow(this)" required>${options}</select>
                <input type="hidden" name="descriptions[]" class="desc-hidden">
                <input type="hidden" name="types[]" value="service">
                <input type="hidden" name="medicine_ids[]" value="">
            </td>
            <td class="px-6 py-4"><input type="number" name="prices[]" value="0.00" step="0.01" class="price-input block w-full bg-gray-50 border-gray-300 rounded-md sm:text-sm text-right" readonly></td>
            <td class="px-6 py-4"><input type="number" name="quantities[]" value="1" min="1" class="qty-input block w-full border-gray-300 rounded-md sm:text-sm text-center" oninput="calculateRowTotal(this)" required></td>
            <td class="px-6 py-4 text-right text-sm font-medium text-gray-900 row-total">$0.00</td>
            <td class="px-6 py-4 text-center"><button type="button" onclick="removeRow(this)" class="text-red-600 hover:text-red-900">x</button></td>
        `;
        tbody.appendChild(tr);
    }

    function updateLabTestRow(select) {
        const row = select.closest('tr');
        const option = select.options[select.selectedIndex];
        if (option.value) {
            row.querySelector('.price-input').value = option.dataset.price;
            row.querySelector('.desc-hidden').value = option.dataset.name;
            calculateRowTotal(select);
        }
    }

    function calculateRowTotal(element) {
        const row = element.closest('tr');
        const price = parseFloat(row.querySelector('.price-input').value) || 0;
        const qty = parseInt(row.querySelector('.qty-input').value) || 0;
        row.querySelector('.row-total').textContent = '$' + (price * qty).toFixed(2);
        updateGrandTotal();
    }

    function removeRow(btn) { btn.closest('tr').remove(); updateGrandTotal(); }
    function updateGrandTotal() {
        let total = 0;
        document.querySelectorAll('tbody tr').forEach(row => {
            const price = parseFloat(row.querySelector('.price-input').value) || 0;
            const qty = parseInt(row.querySelector('.qty-input').value) || 0;
            total += (price * qty);
        });
        document.getElementById('grandTotal').textContent = '$' + total.toFixed(2);
    }
    
    // Inject patient_id before submit if OPD/IPD
    document.getElementById('invoiceForm').addEventListener('submit', function(e) {
        const cat = document.querySelector('input[name="category"]:checked').value;
        const patientSelect = document.getElementById('patient_id');
        
        // If OPD, we need to fetch patient_id from appointment logic or hidden field? 
        // The controller for OPD looks up patient via appointment relation IF it's null? 
        // No, I wrote: $patient = Patient::findOrFail($request->patient_id);
        // So I MUST send patient_id.
        
        // Critical: I need to ensure patient_id is populated for OPD. 
        // Since I can't easily get it from the appointment dropdown JS without an extra lookup, 
        // I will rely on the server to fill it if missing? No, `required` validation will fail.
        
        // FIX: For OPD, I will make the patient_id field NOT required in the controller if appointment_id is present, 
        // OR I fetch it.
        // Let's update the Controller one last time to be smart about patient_id for OPD.
    });

</script>
@endsection
