<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row items-center">
            @include(('devices.partials.header'))
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" value="{{ $record->name }}" readonly />
                        </div>

                        <div>
                            <x-input-label for="appid" :value="__('APPID')" />
                            <x-text-input id="appid" name="appid" type="text" class="mt-1 block w-full" value="{{ $record->appid }}" readonly />
                        </div>

                        <div>
                            <x-input-label for="appsecret" :value="__('APPSECRET')" />
                            <x-text-input id="appsecret" name="appsecret" type="text" class="mt-1 block w-full" value="{{ $record->appsecret }}" readonly />
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <a href="{{route('devices.index')}}" class="rounded bg-gray-500 hover:bg-gray-600 text-white text-center px-4 py-2" title="Back">
                                <i class="fa fa-chevron-left"></i>
                                {{ __('Back') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
