<div x-data="{ 
    count: 0, 
    alerts: [],
    open: false,
    fetchAlerts() {
        fetch('{{ route('alerts.fetch') }}')
            .then(res => res.json())
            .then(data => {
                this.count = data.count;
                this.alerts = data.alerts;
            });
    }
}" x-init="fetchAlerts(); setInterval(() => fetchAlerts(), 30000)" class="relative mr-4" @click.away="open = false">

    <!-- Bell Icon -->
    <button @click="open = !open" class="relative p-2 text-slate-400 hover:text-slate-600 transition focus:outline-none">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
        </svg>
        
        <!-- Badge -->
        <span x-show="count > 0" x-text="count" class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white transform translate-x-1/4 -translate-y-1/4 bg-red-600 rounded-full animate-pulse border border-white"></span>
    </button>

    <!-- Dropdown List -->
    <div x-show="open" 
         style="display: none;" 
         class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-2xl overflow-hidden z-50 border border-slate-100 ring-1 ring-black ring-opacity-5"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translateY-2"
         x-transition:enter-end="opacity-100 translateY-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translateY-0"
         x-transition:leave-end="opacity-0 translateY-2">
         
         <div class="px-4 py-3 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
            <h3 class="text-sm font-bold text-slate-800">System Alerts</h3>
            <span class="text-xs text-slate-500" x-text="count + ' Unresolved'"></span>
         </div>
         
         <div class="max-h-64 overflow-y-auto">
            <template x-for="alert in alerts" :key="alert.id">
                <div class="p-3 border-b border-slate-50 hover:bg-slate-50 transition">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 pt-0.5">
                            <span class="w-2 h-2 rounded-full bg-red-500 inline-block"></span>
                        </div>
                        <div class="ml-3 w-0 flex-1">
                            <p class="text-xs font-medium text-slate-900" x-text="alert.message"></p>
                            <p class="text-[10px] text-slate-400 mt-1" x-text="new Date(alert.created_at).toLocaleTimeString()"></p>
                        </div>
                    </div>
                </div>
            </template>
            
            <template x-if="alerts.length === 0">
                 <div class="p-6 text-center text-slate-400 text-sm">
                    No active alerts.
                </div>
            </template>
         </div>
         
         <a href="{{ route('alerts.index') }}" class="block text-center px-4 py-2 text-xs font-bold text-indigo-600 bg-slate-50 hover:bg-slate-100 transition">View All Alerts</a>
    </div>
</div>
