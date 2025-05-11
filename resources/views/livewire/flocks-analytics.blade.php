<div>
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold text-gray-800">Flock Analytics</h1>
            
            <div class="flex space-x-4">
                <!-- Date Range Selector -->
                <select wire:model="dateRange" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="7">Last 7 Days</option>
                    <option value="30">Last 30 Days</option>
                    <option value="90">Last 90 Days</option>
                    <option value="180">Last 180 Days</option>
                    <option value="365">Last Year</option>
                </select>
                
                <!-- Flock Selector -->
                <select wire:model="selectedFlock" wire:change="selectFlock($event.target.value)" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    @foreach($flocks as $flock)
                        <option value="{{ $flock->id }}">{{ $flock->name }}</option>
                    @endforeach
                </select>
                
                <!-- Export Button -->
                <button wire:click="exportFlockData" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Export
                </button>
            </div>
        </div>
        
        @if($flockData)
            <!-- Flock Summary -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-gray-50 rounded-lg p-4 shadow-sm">
                    <h3 class="text-sm font-medium text-gray-500">Flock Name</h3>
                    <p class="text-xl font-semibold">{{ $flockData->name }}</p>
                </div>
                
                <div class="bg-gray-50 rounded-lg p-4 shadow-sm">
                    <h3 class="text-sm font-medium text-gray-500">Age</h3>
                    <p class="text-xl font-semibold">{{ \Carbon\Carbon::parse($flockData->hatch_date)->diffInWeeks() ?? 0 }} weeks</p>
                </div>
                
                <div class="bg-gray-50 rounded-lg p-4 shadow-sm">
                    <h3 class="text-sm font-medium text-gray-500">Current Count</h3>
                    <p class="text-xl font-semibold">{{ $flockData->current_count }} birds</p>
                </div>
                
                <div class="bg-gray-50 rounded-lg p-4 shadow-sm">
                    <h3 class="text-sm font-medium text-gray-500">Mortality Rate</h3>
                    <p class="text-xl font-semibold">{{ $healthData['mortality'] }}%</p>
                </div>
            </div>
            
            <!-- Main Analytics Panels -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Egg Production Panel -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-800">Egg Production</h2>
                    </div>
                    
                    <div class="p-4">
                        <!-- Key Metrics -->
                        <div class="grid grid-cols-3 gap-4 mb-4">
                            <div class="text-center">
                                <p class="text-xs text-gray-500 uppercase">Total Eggs</p>
                                <p class="text-xl font-bold">{{ number_format($productionData['total']) }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-xs text-gray-500 uppercase">Daily Avg</p>
                                <p class="text-xl font-bold">{{ number_format($productionData['daily_avg'], 1) }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-xs text-gray-500 uppercase">Laying Rate</p>
                                <p class="text-xl font-bold">{{ $productionData['laying_rate'] }}%</p>
                            </div>
                        </div>
                        
                        <!-- Chart -->
                        <div class="h-48">
                            <div id="egg-production-chart" class="w-full h-full"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Feed Usage Panel -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-800">Feed Usage</h2>
                    </div>
                    
                    <div class="p-4">
                        <!-- Key Metrics -->
                        <div class="grid grid-cols-3 gap-4 mb-4">
                            <div class="text-center">
                                <p class="text-xs text-gray-500 uppercase">Total Feed</p>
                                <p class="text-xl font-bold">{{ number_format($feedData['total']) }} kg</p>
                            </div>
                            <div class="text-center">
                                <p class="text-xs text-gray-500 uppercase">Daily Avg</p>
                                <p class="text-xl font-bold">{{ number_format($feedData['daily_avg'], 1) }} kg</p>
                            </div>
                            <div class="text-center">
                                <p class="text-xs text-gray-500 uppercase">Feed Conversion</p>
                                <p class="text-xl font-bold">{{ $feedData['feed_conversion'] }}</p>
                            </div>
                        </div>
                        
                        <!-- Chart -->
                        <div class="h-48">
                            <div id="feed-usage-chart" class="w-full h-full"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Health Records Panel -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-800">Health Status</h2>
                    </div>
                    
                    <div class="p-4">
                        <!-- Key Metrics -->
                        <div class="grid grid-cols-3 gap-4 mb-4">
                            <div class="text-center">
                                <p class="text-xs text-gray-500 uppercase">Active Treatments</p>
                                <p class="text-xl font-bold">{{ $healthData['active_treatments'] }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-xs text-gray-500 uppercase">Total Treatments</p>
                                <p class="text-xl font-bold">{{ $healthData['total_treatments'] }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-xs text-gray-500 uppercase">Mortality</p>
                                <p class="text-xl font-bold">{{ $healthData['mortality'] }}%</p>
                            </div>
                        </div>
                        
                        <!-- Health Records -->
                        <div class="mt-4 max-h-48 overflow-y-auto">
                            @if($healthData['records']->count() > 0)
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($healthData['records'] as $record)
                                            <tr>
                                                <td class="px-3 py-2 text-xs">{{ \Carbon\Carbon::parse($record->date)->format('M d, Y') }}</td>
                                                <td class="px-3 py-2 text-xs">{{ $record->treatment_type }}</td>
                                                <td class="px-3 py-2 text-xs">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $record->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                        {{ ucfirst($record->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="text-center py-4 text-gray-500">No health records found</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-12">
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <h3 class="mt-2 text-lg font-medium text-gray-900">No Flock Selected</h3>
                <p class="mt-1 text-sm text-gray-500">Please select a flock to view analytics.</p>
            </div>
        @endif
    </div>
    
    @if($flockData)
        <!-- JavaScript for charts -->
        <script>
            document.addEventListener('livewire:load', function() {
                // Egg Production Chart
                const eggProductionData = @json($productionData['chart_data']);
                
                if (eggProductionData.length > 0) {
                    const eggChart = new ApexCharts(document.querySelector('#egg-production-chart'), {
                        series: [{
                            name: 'Eggs',
                            data: eggProductionData.map(item => ({ x: item.date, y: item.value }))
                        }],
                        chart: {
                            type: 'line',
                            height: '100%',
                            toolbar: {
                                show: false
                            }
                        },
                        stroke: {
                            curve: 'smooth',
                            width: 3
                        },
                        colors: ['#4f46e5'],
                        xaxis: {
                            type: 'datetime',
                            labels: {
                                format: 'MMM dd',
                                style: {
                                    fontSize: '10px'
                                }
                            }
                        },
                        yaxis: {
                            labels: {
                                formatter: function(val) {
                                    return Math.round(val);
                                },
                                style: {
                                    fontSize: '10px'
                                }
                            }
                        },
                        tooltip: {
                            x: {
                                format: 'dd MMM yyyy'
                            }
                        }
                    });
                    
                    eggChart.render();
                }
                
                // Feed Usage Chart
                const feedUsageData = @json($feedData['chart_data']);
                
                if (feedUsageData.length > 0) {
                    const feedChart = new ApexCharts(document.querySelector('#feed-usage-chart'), {
                        series: [{
                            name: 'Feed (kg)',
                            data: feedUsageData.map(item => ({ x: item.date, y: item.value }))
                        }],
                        chart: {
                            type: 'line',
                            height: '100%',
                            toolbar: {
                                show: false
                            }
                        },
                        stroke: {
                            curve: 'smooth',
                            width: 3
                        },
                        colors: ['#f59e0b'],
                        xaxis: {
                            type: 'datetime',
                            labels: {
                                format: 'MMM dd',
                                style: {
                                    fontSize: '10px'
                                }
                            }
                        },
                        yaxis: {
                            labels: {
                                formatter: function(val) {
                                    return Math.round(val);
                                },
                                style: {
                                    fontSize: '10px'
                                }
                            }
                        },
                        tooltip: {
                            x: {
                                format: 'dd MMM yyyy'
                            }
                        }
                    });
                    
                    feedChart.render();
                }
                
                // Listen for Livewire updates to refresh charts
                Livewire.on('flockDataUpdated', () => {
                    // We would update the charts here if needed
                    // This would require a custom event from the PHP component
                });
            });
        </script>
    @endif
</div>