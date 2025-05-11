<div class="">
    <h1 class="text-2xl font-bold">Egg Production Management</h1>
    <nav class="flex py-3 mb-4">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a>
            </li>
            <li class="flex items-center">
                <svg class="w-3 h-3 mx-1 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                </svg>
                <span class="ml-1 text-gray-500 md:ml-2">Eggs</span>
            </li>
        </ol>
    </nav>
    
    
    <div class="border-b border-gray-200 bg-gray-50 px-2 py-3 flex justify-between items-center mb-2">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M5 4a3 3 0 00-3 3v6a3 3 0 003 3h10a3 3 0 003-3V7a3 3 0 00-3-3H5zm-1 9v-1h5v2H5a1 1 0 01-1-1zm7 1h4a1 1 0 001-1v-1h-5v2zm0-4h5V8h-5v2zM9 8H4v2h5V8z" clip-rule="evenodd"></path>
            </svg>
            <span class="text-lg font-semibold">All Eggs</span>
        </div>
        <div>
            <a variant="primary" href="/eggs/add">{{ __('Add Eggs') }}</a>
        </div>
    </div>
    <livewire:eggs-table/>

</div>