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
                        <p class="mt-2 text-sm text-gray-500">
                            Ваш статус: обычный пользователь
                        </p>
                    @endunless
                </div>
            </div>
            @if(auth()->user()->hasRole('admin'))
                <!-- Блок управления ролями -->
                <div class="bg-white shadow sm:rounded-lg">
                    <div class="px-6 py-4 border-b">
                        <h3 class="text-lg font-medium text-gray-900">Управление ролями</h3>
                    </div>
                    <div class="p-6">
                        <livewire:role-by-user />
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
