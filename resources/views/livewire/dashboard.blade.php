<div>
    <!-- Top Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm">Active Flocks</p>
                    <p class="text-lg font-semibold">{{ $activeFlocks }}</p>
                    <p class="text-xs text-gray-500">Total: {{ $totalFlocks }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm">Eggs Today</p>
                    <p class="text-lg font-semibold">{{ $eggProductionToday }}</p>
                    <p class="text-xs text-gray-500">Week: {{ $eggProductionWeek }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm">Feed Used Today</p>
                    <p class="text-lg font-semibold">{{ $feedUsageToday }} kg</p>
                    <p class="text-xs text-gray-500">Week: {{ $feedUsageWeek }} kg</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm">Health Issues</p>
                    <p class="text-lg font-semibold">{{ $activeTreatments }}</p>
                    <p class="text-xs text-gray-500">Mortality: {{ $mortalityRate }}%</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Middle Stats - Performance Indicators -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-lg font-medium mb-2">Current Flock Status</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metric</th>
                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Value</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td class="px-2 py-2 whitespace-nowrap text-sm text-gray-500">Total Birds</td>
                            <td class="px-2 py-2 whitespace-nowrap text-sm font-medium">{{ $totalBirds }}</td>
                        </tr>
                        <tr>
                            <td class="px-2 py-2 whitespace-nowrap text-sm text-gray-500">Average Laying Rate</td>
                            <td class="px-2 py-2 whitespace-nowrap text-sm font-medium">{{ $averageLayingRate }}%</td>
                        </tr>
                        <tr>
                            <td class="px-2 py-2 whitespace-nowrap text-sm text-gray-500">Feed Stock</td>
                            <td class="px-2 py-2 whitespace-nowrap text-sm font-medium">{{ $feedStock }} kg</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex justify-between items-center mb-4">
                
                <h3 class="text-lg font-medium">Data Trends</h3>

                <div>
                    {{-- <flux:select
                    wire:change="$emit('dateRangeUpdated', $event.target.value)" 
                    >
                    <flux:select.option value="week" {{ $selectedDateRange == 'week' ? 'selected' : '' }}>Last Week</flux:select.option>
                    <flux:select.option value="month" {{ $selectedDateRange == 'month' ? 'selected' : '' }}>Last Month</flux:select.option>
                    <flux:select.option value="quarter" {{ $selectedDateRange == 'quarter' ? 'selected' : '' }}>Last Quarter</flux:select.option>
                    <flux:select.option value="year" {{ $selectedDateRange == 'year' ? 'selected' : '' }}>Last Year</flux:select.option>
                    </flux:select> --}}
                    <select wire:change="$emit(dateRangeUpdated($event.target.value))" class="text-sm border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 p-2">
                        <option value="week" {{ $selectedDateRange == 'week' ? 'selected' : '' }}>Last Week</option>
                        <option value="month" {{ $selectedDateRange == 'month' ? 'selected' : '' }}>Last Month</option>
                        <option value="quarter" {{ $selectedDateRange == 'quarter' ? 'selected' : '' }}>Last Quarter</option>
                        <option value="year" {{ $selectedDateRange == 'year' ? 'selected' : '' }}>Last Year</option>
                    </select>
                </div>
            </div>
            <flux:separator />
            <div class="w-full h-64" id="trend-chart"></div>
        </div>
    </div>
    
    <!-- Bottom Section - Recent Activities -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <h3 class="text-lg font-medium mb-2">Quick Actions</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <a href="{{ route('flocks.add') }}" class="block p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                <div class="flex items-center">
                    <div class="p-2 rounded-full bg-blue-100 text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </div>
                    <span class="ml-2 text-sm font-medium">Add Flock</span>
                </div>
            </a>
            
            <a href="{{ route('eggs.add') }}" class="block p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                <div class="flex items-center">
                    <div class="p-2 rounded-full bg-green-100 text-green-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </div>
                    <span class="ml-2 text-sm font-medium">Record Eggs</span>
                </div>
            </a>
            
            <a href="{{ route('feeds') }}" class="block p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors">
                <div class="flex items-center">
                    <div class="p-2 rounded-full bg-yellow-100 text-yellow-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </div>
                    <span class="ml-2 text-sm font-medium">Record Feed</span>
                </div>
            </a>
            
            <a href="{{ route('health') }}" class="block p-4 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">
                <div class="flex items-center">
                    <div class="p-2 rounded-full bg-red-100 text-red-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </div>
                    <span class="ml-2 text-sm font-medium">Health Record</span>
                </div>
            </a>
        </div>
    </div>
    
    <!-- ApexCharts JS -->
    @push('scripts')
    {{-- <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script> --}}
    <script>
        console.log("i am here sir !!!");
        
        document.addEventListener('livewire:load', function () {
            var options = {
                chart: {
                    type: 'line',
                    height: 256,
                    toolbar: {
                        show: false
                    }
                },
                series: [{
                    name: 'Eggs',
                    data: @json(collect($chartData)->pluck('eggs'))
                }, {
                    name: 'Feed (kg)',
                    data: @json(collect($chartData)->pluck('feed'))
                }],
                xaxis: {
                    categories: @json(collect($chartData)->pluck('date')),
                    labels: {
                        rotate: -45,
                        rotateAlways: true
                    }
                },
                yaxis: {
                    title: {
                        text: 'Quantity'
                    }
                },
                colors: ['#10B981', '#FBBF24'],
                stroke: {
                    curve: 'smooth',
                    width: 2
                },
                legend: {
                    position: 'top'
                }
            };
            
            var chart = new ApexCharts(document.querySelector("#trend-chart"), options);
            chart.render();
            
            // Listen for date range updates
            Livewire.on('chartDataUpdated', (data) => {
                chart.updateOptions({
                    xaxis: {
                        categories: data.map(item => item.date)
                    }
                });
                chart.updateSeries([{
                    name: 'Eggs',
                    data: data.map(item => item.eggs)
                }, {
                    name: 'Feed (kg)',
                    data: data.map(item => item.feed)
                }]);
            });
        });
        
        // Update chart when the component is updated
        document.addEventListener('livewire:update', function() {
            if (window.apexCharts) {
                window.apexCharts.forEach(chart => {
                    chart.updateOptions({
                        xaxis: {
                            categories: @json(collect($chartData)->pluck('date'))
                        }
                    });
                    chart.updateSeries([{
                        name: 'Eggs',
                        data: @json(collect($chartData)->pluck('eggs'))
                    }, {
                        name: 'Feed (kg)',
                        data: @json(collect($chartData)->pluck('feed'))
                    }]);
                });
            }
        });
    </script>
    @endpush
</div>