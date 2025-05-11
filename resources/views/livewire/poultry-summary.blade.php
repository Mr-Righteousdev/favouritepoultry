<div class="py-4">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Poultry Farm Summary</h2>
        <div class="flex space-x-3">
            <select wire:model="timeframe" class="border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                <option value="week">Last Week</option>
                <option value="month">Last Month</option>
                <option value="quarter">Last Quarter</option>
                <option value="year">Last Year</option>
            </select>
            <button wire:click="refreshData" class="flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Refresh
            </button>
            <button wire:click="exportSummary" class="flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Export
            </button>
        </div>
    </div>

    <!-- Flock Statistics -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Flock Overview</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-blue-50 rounded-lg p-4">
                <p class="text-sm text-blue-600 font-medium">Total Flocks</p>
                <p class="text-3xl font-bold">{{ $flockData['total'] }}</p>
                <p class="text-sm text-gray-500">Active: {{ $flockData['active'] }}</p>
            </div>
            <div class="bg-green-50 rounded-lg p-4">
                <p class="text-sm text-green-600 font-medium">Active Birds</p>
                <p class="text-3xl font-bold">{{ $flockData['birds'] }}</p>
                <p class="text-sm text-gray-500">Across all active flocks</p>
            </div>
            <div class="bg-yellow-50 rounded-lg p-4">
                <p class="text-sm text-yellow-600 font-medium">Health Status</p>
                <p class="text-3xl font-bold">{{ $healthData['active_treatments'] }}</p>
                <p class="text-sm text-gray-500">Active treatments</p>
            </div>
        </div>

        <h4 class="text-md font-medium text-gray-700 mt-6 mb-3">Recent Flocks</h4>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Breed</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Initial Count</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Count</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Added</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($flockData['recent'] as $flock)
                        <tr>
                            <td class="px-3 py-2 text-sm">{{ $flock['name'] }}</td>
                            <td class="px-3 py-2 text-sm">{{ $flock['breed'] }}</td>
                            <td class="px-3 py-2 text-sm">{{ $flock['initial_count'] }}</td>
                            <td class="px-3 py-2 text-sm">{{ $flock['current_count'] }}</td>
                            <td class="px-3 py-2 text-sm">{{ Carbon\Carbon::parse($flock['created_at'])->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-3 py-2 text-sm text-gray-500 text-center">No flocks found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Production Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Egg Production -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Egg Production</h3>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="bg-green-50 rounded-lg p-3">
                    <p class="text-sm text-green-600 font-medium">Total Collection</p>
                    <p class="text-2xl font-bold">{{ number_format($eggData['total']) }}</p>
                    <p class="text-sm text-gray-500">Avg: {{ number_format($eggData['average'], 0) }}/day</p>
                </div>
                <div class="bg-blue-50 rounded-lg p-3">
                    <p class="text-sm text-blue-600 font-medium">Today's Collection</p>
                    <p class="text-2xl font-bold">{{ number_format($eggData['today']) }}</p>
                    <p class="text-sm text-gray-500">
                        @if($eggData['today'] > $eggData['average'])
                        <span class="text-green-500">↑ Above average</span>
                        @elseif($eggData['today'] < $eggData['average'])
                        <span class="text-red-500">↓ Below average</span>
                        @else
                        At average
                        @endif
                    </p>
                </div>
            </div>
            <div class="h-56" id="egg-chart"></div>
        </div>

        <!-- Feed Usage -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Feed Consumption</h3>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="bg-yellow-50 rounded-lg p-3">
                    <p class="text-sm text-yellow-600 font-medium">Total Usage</p>
                    <p class="text-2xl font-bold">{{ number_format($feedData['total'], 1) }} kg</p>
                    <p class="text-sm text-gray-500">Avg: {{ number_format($feedData['average'], 1) }} kg/day</p>
                </div>
                <div class="bg-orange-50 rounded-lg p-3">
                    <p class="text-sm text-orange-600 font-medium">Today's Usage</p>
                    <p class="text-2xl font-bold">{{ number_format($feedData['today'], 1) }} kg</p>
                    <p class="text-sm text-gray-500">
                        @if($feedData['today'] > $feedData['average'])
                        <span class="text-red-500">↑ Above average</span>
                        @elseif($feedData['today'] < $feedData['average'])
                        <span class="text-green-500">↓ Below average</span>
                        @else
                        At average
                        @endif
                    </p>
                </div>
            </div>
            <div class="h-56" id="feed-chart"></div>
        </div>
    </div>

    <!-- Health Records -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Health Status</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-red-50 rounded-lg p-4">
                <p class="text-sm text-red-600 font-medium">Active Treatments</p>
                <p class="text-3xl font-bold">{{ $healthData['active_treatments'] }}</p>
                <p class="text-sm text-gray-500">Requiring attention</p>
            </div>
            <div class="bg-red-50 rounded-lg p-4">
                <p class="text-sm text-red-600 font-medium">Mortality</p>
                <p class="text-3xl font-bold">{{ $healthData['mortality'] }}</p>
                <p class="text-sm text-gray-500">In selected period</p>
            </div>
            <div class="bg-green-50 rounded-lg p-4">
                <p class="text-sm text-green-600 font-medium">Quick Action</p>
                <a href="{{ route('health-records.create') }}" class="mt-2 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Add Health Record
                </a>
            </div>
        </div>

        <h4 class="text-md font-medium text-gray-700 mb-3">Recent Health Records</h4>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Flock</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Issue</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Treatment</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($healthData['recent'] as $record)
                        <tr>
                            <td class="px-3 py-2 text-sm">{{ Carbon\Carbon::parse($record['date'])->format('M d, Y') }}</td>
                            <td class="px-3 py-2 text-sm">
                                @php
                                    $flockName = App\Models\Flock::find($record['flock_id'])?->name ?? 'Unknown';
                                @endphp
                                {{ $flockName }}
                            </td>
                            <td class="px-3 py-2 text-sm">{{ \Illuminate\Support\Str::limit($record['issue'], 30) }}</td>
                            <td class="px-3 py-2 text-sm">{{ \Illuminate\Support\Str::limit($record['treatment'], 30) }}</td>
                            <td class="px-3 py-2 text-sm">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $record['status'] === 'active' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                    {{ ucfirst($record['status']) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-3 py-2 text-sm text-gray-500 text-center">No health records found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('livewire:load', function() {
            const eggChartOptions = {
                chart: {
                    type: 'line',
                    height: 224,
                    toolbar: {
                        show: false
                    },
                    animations: {
                        enabled: true
                    }
                },
                series: [{
                    name: 'Eggs',
                    data: @json(collect($eggData['chart'])->pluck('value'))
                }],
                xaxis: {
                    categories: @json(collect($eggData['chart'])->pluck('label')),
                    labels: {
                        rotate: -45,
                        rotateAlways: true
                    }
                },
                colors: ['#10B981'],
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                markers: {
                    size: 4
                }
            };
            
            const feedChartOptions = {
                chart: {
                    type: 'area',
                    height: 224,
                    toolbar: {
                        show: false
                    },
                    animations: {
                        enabled: true
                    }
                },
                series: [{
                    name: 'Feed (kg)',
                    data: @json(collect($feedData['chart'])->pluck('value'))
                }],
                xaxis: {
                    categories: @json(collect($feedData['chart'])->pluck('label')),
                    labels: {
                        rotate: -45,
                        rotateAlways: true
                    }
                },
                colors: ['#F59E0B'],
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.7,
                        opacityTo: 0.3
                    }
                }
            };
            
            const eggChart = new ApexCharts(document.querySelector("#egg-chart"), eggChartOptions);
            const feedChart = new ApexCharts(document.querySelector("#feed-chart"), feedChartOptions);
            
            eggChart.render();
            feedChart.render();
            
            // Update charts when data changes
            Livewire.on('chartDataUpdated', function() {
                eggChart.updateOptions({
                    xaxis: {
                        categories: @json(collect($eggData['chart'])->pluck('label'))
                    }
                });
                eggChart.updateSeries([{
                    name: 'Eggs',
                    data: @json(collect($eggData['chart'])->pluck('value'))
                }]);
                
                feedChart.updateOptions({
                    xaxis: {
                        categories: @json(collect($feedData['chart'])->pluck('label'))
                    }
                });
                feedChart.updateSeries([{
                    name: 'Feed (kg)',
                    data: @json(collect($feedData['chart'])->pluck('value'))
                }]);
            });
        });
    </script>
    @endpush
</div>