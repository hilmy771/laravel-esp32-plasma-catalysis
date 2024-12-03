<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight basis-10/12">
    {{ $title }}
</h2>
@if (isset($isAllowCreate) && $isAllowCreate)
    <a href="{{route('receivers.create')}}" class="rounded bg-green-500 hover:bg-green-600 text-white text-center px-4 py-2 basis-2/12" title="Add">
        <i class="fa fa-plus"></i>
        {{ __('New Data') }}
    </a>
@endif