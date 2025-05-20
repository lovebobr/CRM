<div class="space-y-6">
    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-6 py-4 border-b">
            <h3 class="text-lg font-medium text-gray-900">Управление ролями</h3>
        </div>
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-xl font-bold text-gray-800 mb-4">
            @if($isEditing)
                Редактировать роль
            @else
                Создать новую роль
            @endif
        </h3>

        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
                {{ session('error') }}
            </div>
        @endif

        <form wire:submit.prevent="saveRole">
            <!-- Название роли -->
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Название роли*</label>
                <input wire:model="roleName" type="text"
                       class="w-full px-4 py-2 border rounded focus:ring-blue-500 focus:border-blue-500">
                @error('roleName') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <!-- Чекбоксы разрешений -->
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Разрешения:</label>
                <div class="space-y-2 max-h-60 overflow-y-auto p-1">
                    @foreach($availablePermissions as $permission)
                        <label class="flex items-center p-3 border rounded hover:bg-gray-50 cursor-pointer">
                            <input wire:model="selectedPermissions"
                                   type="checkbox"
                                   value="{{ $permission }}"
                                   class="h-5 w-5 text-blue-600 rounded mr-3 focus:ring-blue-500">
                            <span class="text-gray-700">{{ $permission }}</span>
                        </label>
                    @endforeach
                </div>
                @error('selectedPermissions') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <!-- Кнопки -->
            <div class="flex space-x-3">
                <button type="submit"
                        class="mt-3 w-full text-white bg-pink-300 hover:bg-pink-400 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">
                    @if($isEditing)
                        Обновить
                    @else
                        Создать
                    @endif
                </button>

                @if($isEditing)
                    <button type="button" wire:click="resetForm"
                            class="mt-3  px-6 py-2 bg-gray-200 text-gray-800 font-medium rounded hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                        Отмена
                    </button>
                @endif
            </div>
        </form>
    </div>

    <!-- Список ролей -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Список ролей</h3>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Роль</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Разрешения</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Действия</th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @forelse($roles as $role)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                            {{ $role->name }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                @forelse($role->permissions as $permission)
                                    <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">
                                        {{ $permission->name }}
                                    </span>
                                @empty
                                    <span class="text-gray-500 text-sm">Нет разрешений</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button wire:click="editRole({{ $role->id }})"
                                    class="text-blue-600 hover:text-blue-900 mr-3 focus:outline-none">
                                Изменить
                            </button>
                            <button wire:click="deleteRole({{ $role->id }})"
                                    onclick="return confirm('Вы уверены, что хотите удалить эту роль?')"
                                    class="text-red-600 hover:text-red-900 focus:outline-none">
                                Удалить
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                            Нет созданных ролей
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
