<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row items-center">
            @include(('devices.partials.header'))
        </div>
    </x-slot>
    @if (in_array(session('status'), ['record-created', 'record-updated', 'record-deleted']))
        <div 
            x-data="{ show: true }"
            x-show="show"
            x-transition
            x-init="setTimeout(() => show = false, 3000)"
            class="flex items-center bg-green-500 text-white text-sm font-bold p-4" 
            role="alert">
            <p>
                <i class="fa fa-check-circle"></i>
                @if (session('status') == 'record-created')
                    {{ __('New data has been saved.') }}
                @elseif (session('status') == 'record-updated')
                    {{ __('Data has been updated.') }}
                @elseif (session('status') == 'record-deleted')
                    {{ __('Data has been deleted.') }}
                @endif
            </p>
        </div>
    @elseif (in_array(session('status'), ['404']))
        <div 
            x-data="{ show: true }"
            x-show="show"
            x-transition
            x-init="setTimeout(() => show = false, 3000)"
            class="flex items-center bg-red-500 text-white text-sm font-bold p-4" 
            role="alert">
            <p>
                <i class="fa fa-times-circle"></i>
                @if (session('status') == '404')
                    {{ __('Data is not found.') }}
                @endif
            </p>
        </div>
    @endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @include(('devices.partials.table'))
                </div>
            </div>
        </div>
    </div>
</x-app-layout>