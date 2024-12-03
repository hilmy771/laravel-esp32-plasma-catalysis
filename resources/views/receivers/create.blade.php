<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row items-center">
            @include(('receivers.partials.header'))
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('receivers.store') }}" method="post" class="grid grid-cols-1 gap-4">
                        @csrf
                        @method('post')

                        <div>
                            <x-input-label for="device_id" :value="__('Device')" />
                            <x-select name="device_id" id="device_id" class="mt-1 block w-full">
                                <option value="">Select Device</option>
                                @foreach ($devices as $device)
                                    <option value="{{ $device->id }}">{{ $device->name }}</option>
                                @endforeach
                            </x-select>
                            <x-input-error :messages="$errors->get('device_id')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="type" :value="__('Type')" />
                            <x-select name="type" id="type" class="mt-1 block w-full">
                                <option value="">Select Type</option>
                                <option value="sensor">Sensor</option>
                            </x-select>
                            <x-input-error :messages="$errors->get('type')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="name" :value="__('Body')" />
                            <x-textarea name="body" id="body" class="mt-1 block w-full resize-none"></x-textarea>
                            <x-input-error :messages="$errors->get('body')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <a href="{{route('receivers.index')}}" class="rounded bg-gray-500 hover:bg-gray-600 text-white text-center px-4 py-2" title="Back">
                                <i class="fa fa-chevron-left"></i>
                                {{ __('Back') }}
                            </a>

                            <button type="submit" class="rounded bg-green-500 hover:bg-green-600 text-white text-center px-4 py-2" title="Save">
                                <i class="fa fa-save"></i>
                                {{ __('Save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
