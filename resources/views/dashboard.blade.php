<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Главная панель') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("Добро пожаловать в систему!") }}
                    @unless(auth()->user()->hasRole('admin'))

                    @endunless
                </div>
            </div>
            @if(auth()->user()->hasRole('admin'))
                <div class="space-y-6">
                    <div class="bg-white shadow sm:rounded-lg">
                        <div class="px-6 py-4 border-b">
                            <h3 class="text-lg font-medium text-gray-900">Моя статистика</h3>
                        </div>
                        <div class="p-6">
                            <livewire:manager-stats />
                        </div>
                    </div>
                </div>
            @elseif(auth()->user()->hasRole('manager'))
                <!-- Дашборд менеджера -->
                <div class="space-y-6">
                    <div class="bg-white shadow sm:rounded-lg">
                        <div class="px-6 py-4 border-b">
                            <h3 class="text-lg font-medium text-gray-900">Дашборд менеджера</h3>
                        </div>
                        <div class="p-6">
                            <livewire:manager-dashboard />
                        </div>
                    </div>
                </div>
            @elseif(auth()->user()->hasRole('partner'))
                <div class="space-y-6">
                    <div class="bg-white shadow sm:rounded-lg">
                        <div class="px-6 py-4 border-b">
                            <h3 class="text-lg font-medium text-gray-900">Панель партнёра</h3>
                        </div>
                        <div class="p-6">
                            <livewire:partner-dashboard />
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
