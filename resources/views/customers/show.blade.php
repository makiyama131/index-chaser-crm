{{-- resources/views/customers/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        @include('customers.partials._header_actions')
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 space-y-10">

                    @include('customers.partials._basic_info')

                    @include('customers.partials._tags')

                    @include('customers.partials._documents_section')

                    @include('customers.partials._tasks_section')

                    @include('customers.partials._activities_section')

                </div>
            </div>
        </div>
    </div>
</x-app-layout>