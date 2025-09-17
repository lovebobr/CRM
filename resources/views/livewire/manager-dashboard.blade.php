<div>
    <!-- Информация о менеджере -->
    <div class="bg-white shadow sm:rounded-lg mb-6">
        <div class="px-6 py-4 border-b">
            <h3 class="text-lg font-medium text-gray-900">Ваш профиль</h3>
        </div>
        <div class="p-6 grid grid-cols-2 gap-4">
            <div>
                <p class="text-sm font-medium text-gray-500">Имя:</p>
                <p class="mt-1 text-sm text-gray-900">{{ $manager->name }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Email:</p>
                <p class="mt-1 text-sm text-gray-900">{{ $manager->email }}</p>
            </div>
        </div>
    </div>

    <!-- Фильтры и поиск -->
    <div class="bg-white shadow sm:rounded-lg mb-6">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Поиск -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Поиск</label>
                    <div class="relative rounded-md shadow-sm">
                        <input wire:model.live="searchTerm" type="text" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" placeholder="Телефон или описание...">
                    </div>
                </div>

                <!-- Фильтр по статусу -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Статус</label>
                    <select wire:model.live="statusFilter" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        <option value="">Все статусы</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status->id }}">{{ $status->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Фильтр по партнеру -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Партнер</label>
                    <select wire:model.live="partnerFilter" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        <option value="">Все партнеры</option>
                        @foreach($allPartners as $partner)
                            <option value="{{ $partner->id }}">{{ $partner->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Количество на странице -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">На странице</label>
                    <select wire:model.live="perPage" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Список заявок -->
    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-6 py-4 border-b">
            <h3 class="text-lg font-medium text-gray-900">Ваши заявки</h3>
        </div>

        <div class="px-6 py-4 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Телефон</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Статус</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Описание</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Партнер</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Дата</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Действия</th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @forelse($leads as $lead)
                    @if($editingLeadId == $lead->id)
                        <tr class="bg-blue-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input wire:model="phone" type="text" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <select wire:model="status_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    @foreach($statuses as $status)
                                        <option value="{{ $status->id }}">{{ $status->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="px-6 py-4">
                                <textarea wire:model="description" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"></textarea>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <select wire:model="partner_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    <option value="">Не назначен</option>
                                    @foreach($partners as $partner)
                                        <option value="{{ $partner->id }}">{{ $partner->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $lead->created_at->format('d.m.Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button wire:click="updateLead" class="text-green-600 hover:text-green-900 mr-3">Сохранить</button>
                                <button wire:click="cancelEdit" class="text-gray-600 hover:text-gray-900">Отмена</button>
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $lead->phone }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $lead->status->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $lead->description }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $lead->partner?->name ?? 'Не назначен' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $lead->created_at->format('d.m.Y H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button wire:click="editLead({{ $lead->id }})" class="text-blue-600 hover:text-blue-900 mr-3">Редактировать</button>
                                <button wire:click="selectLeadForAssignment({{ $lead->id }})" class="text-indigo-600 hover:text-indigo-900">Назначить</button>
                            </td>
                        </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">Нет назначенных заявок</td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            <div class="mt-4">
                {{ $leads->links() }}
            </div>
        </div>
    </div>

    @if($selectedLeadId)
        @php
            $selectedLead = \App\Models\Lead::with('status')->find($selectedLeadId);
        @endphp

        <div class="fixed inset-0 overflow-y-auto z-50">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Назначить заявку партнеру
                        </h3>
                        <div class="mt-4">
                            <p class="text-sm text-gray-500 mb-2">Телефон: {{ $selectedLead->phone }}</p>
                            <p class="text-sm text-gray-500 mb-2">Описание: {{ $selectedLead->description }}</p>
                            <p class="text-sm text-gray-500 mb-4">Текущий статус: {{ $selectedLead->status->name }}</p>

                            <label class="block text-sm font-medium text-gray-700 mb-1">Выберите партнера:</label>
                            <select wire:model="selectedPartnerId" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                <option value="">Выберите партнера</option>
                                @foreach($partners as $partner)
                                    <option value="{{ $partner->id }}">{{ $partner->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                        <button wire:click="assignToPartner" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:col-start-2 sm:text-sm">
                            Назначить
                        </button>
                        <button wire:click="$set('selectedLeadId', null)" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:col-start-1 sm:text-sm">
                            Отмена
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
