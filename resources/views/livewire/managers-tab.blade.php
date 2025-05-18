<div class="p-4">
    <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-2xl font-semibold mb-4">Менеджеры</h2>

        <form wire:submit.prevent="{{ $managerId ? 'update' : 'create' }}" class="flex flex-wrap items-end gap-4">
            <div class="w-48">
                <label for="name" class="block text-sm font-medium text-gray-700">Имя</label>
                <input wire:model="name" type="text" id="name"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500">
                @error('name') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="w-64">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input wire:model="email" type="email" id="email"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500">
                @error('email') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
            </div>

            @if(!$managerId)
                <div class="w-48">
                    <label for="password" class="block text-sm font-medium text-gray-700">Пароль</label>
                    <input wire:model="password" type="password" id="password"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500">
                    @error('password') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>
            @endif

            <div class="flex gap-2">
                <button type="submit"
                        class="h-10 mt-1 text-white bg-pink-300 hover:bg-pink-400 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5">
                    {{ $managerId ? 'Обновить' : 'Создать' }}
                </button>
                @if($managerId)
                    <button type="button" wire:click="resetForm"
                            class="h-10 mt-1 text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded-lg text-sm px-5">
                        Отмена
                    </button>
                @endif
            </div>
        </form>

        <!-- Таблица менеджеров -->
        <table class="min-w-full mt-6 divide-y divide-gray-200 border-t">
            <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Имя</th>
                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Email</th>
                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Действия</th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            @forelse($managers as $manager)
                <tr>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $manager->name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $manager->email }}</td>
                    <td class="px-6 py-4 text-sm space-x-2">
                        <button wire:click="edit({{ $manager->id }})"
                                class="relative align-middle select-none font-sans font-medium text-center uppercase transition-all disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none w-10 max-w-[40px] h-10 max-h-[40px] rounded-lg text-xs text-gray-900 hover:bg-gray-900/10 active:bg-gray-900/20" type="button">
                                    <span class="absolute top-1/2 left-1/2 transform -translate-y-1/2 -translate-x-1/2">
                                    <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M4.52185 7H7C7.55229 7 8 7.44772 8 8C8 8.55229 7.55228 9 7 9H3C1.89543 9 1 8.10457 1 7V3C1 2.44772 1.44772 2 2 2C2.55228 2 3 2.44772 3 3V5.6754C4.26953 3.8688 6.06062 2.47676 8.14852 1.69631C10.6633 0.756291 13.435 0.768419 15.9415 1.73041C18.448 2.69239 20.5161 4.53782 21.7562 6.91897C22.9963 9.30013 23.3228 12.0526 22.6741 14.6578C22.0254 17.263 20.4464 19.541 18.2345 21.0626C16.0226 22.5842 13.3306 23.2444 10.6657 22.9188C8.00083 22.5931 5.54702 21.3041 3.76664 19.2946C2.20818 17.5356 1.25993 15.3309 1.04625 13.0078C0.995657 12.4579 1.45216 12.0088 2.00445 12.0084C2.55673 12.0079 3.00351 12.4566 3.06526 13.0055C3.27138 14.8374 4.03712 16.5706 5.27027 17.9625C6.7255 19.605 8.73118 20.6586 10.9094 20.9247C13.0876 21.1909 15.288 20.6513 17.0959 19.4075C18.9039 18.1638 20.1945 16.3018 20.7247 14.1724C21.2549 12.043 20.9881 9.79319 19.9745 7.8469C18.9608 5.90061 17.2704 4.3922 15.2217 3.6059C13.173 2.8196 10.9074 2.80968 8.8519 3.57803C7.11008 4.22911 5.62099 5.40094 4.57993 6.92229C4.56156 6.94914 4.54217 6.97505 4.52185 7Z" fill="#0F0F0F"></path> </g></svg>
                                </span>
                        </button>
                        <button wire:click="delete({{ $manager->id }})"
                                class="relative align-middle select-none font-sans font-medium text-center uppercase transition-all disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none w-10 max-w-[40px] h-10 max-h-[40px] rounded-lg text-xs text-gray-900 hover:bg-gray-900/10 active:bg-gray-900/20" type="button">
                                 <span class="absolute top-1/2 left-1/2 transform -translate-y-1/2 -translate-x-1/2">
                                <svg width="25px" height="25px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12 2.75C11.0215 2.75 10.1871 3.37503 9.87787 4.24993C9.73983 4.64047 9.31134 4.84517 8.9208 4.70713C8.53026 4.56909 8.32557 4.1406 8.46361 3.75007C8.97804 2.29459 10.3661 1.25 12 1.25C13.634 1.25 15.022 2.29459 15.5365 3.75007C15.6745 4.1406 15.4698 4.56909 15.0793 4.70713C14.6887 4.84517 14.2602 4.64047 14.1222 4.24993C13.813 3.37503 12.9785 2.75 12 2.75Z" fill="#1C274C"></path> <path d="M2.75 6C2.75 5.58579 3.08579 5.25 3.5 5.25H20.5001C20.9143 5.25 21.2501 5.58579 21.2501 6C21.2501 6.41421 20.9143 6.75 20.5001 6.75H3.5C3.08579 6.75 2.75 6.41421 2.75 6Z" fill="#1C274C"></path> <path d="M5.91508 8.45011C5.88753 8.03681 5.53015 7.72411 5.11686 7.75166C4.70356 7.77921 4.39085 8.13659 4.41841 8.54989L4.88186 15.5016C4.96735 16.7844 5.03641 17.8205 5.19838 18.6336C5.36678 19.4789 5.6532 20.185 6.2448 20.7384C6.83639 21.2919 7.55994 21.5307 8.41459 21.6425C9.23663 21.75 10.2751 21.75 11.5607 21.75H12.4395C13.7251 21.75 14.7635 21.75 15.5856 21.6425C16.4402 21.5307 17.1638 21.2919 17.7554 20.7384C18.347 20.185 18.6334 19.4789 18.8018 18.6336C18.9637 17.8205 19.0328 16.7844 19.1183 15.5016L19.5818 8.54989C19.6093 8.13659 19.2966 7.77921 18.8833 7.75166C18.47 7.72411 18.1126 8.03681 18.0851 8.45011L17.6251 15.3492C17.5353 16.6971 17.4712 17.6349 17.3307 18.3405C17.1943 19.025 17.004 19.3873 16.7306 19.6431C16.4572 19.8988 16.083 20.0647 15.391 20.1552C14.6776 20.2485 13.7376 20.25 12.3868 20.25H11.6134C10.2626 20.25 9.32255 20.2485 8.60915 20.1552C7.91715 20.0647 7.54299 19.8988 7.26957 19.6431C6.99616 19.3873 6.80583 19.025 6.66948 18.3405C6.52891 17.6349 6.46488 16.6971 6.37503 15.3492L5.91508 8.45011Z" fill="#1C274C"></path> <path d="M9.42546 10.2537C9.83762 10.2125 10.2051 10.5132 10.2464 10.9254L10.7464 15.9254C10.7876 16.3375 10.4869 16.7051 10.0747 16.7463C9.66256 16.7875 9.29502 16.4868 9.25381 16.0746L8.75381 11.0746C8.71259 10.6625 9.0133 10.2949 9.42546 10.2537Z" fill="#1C274C"></path> <path d="M15.2464 11.0746C15.2876 10.6625 14.9869 10.2949 14.5747 10.2537C14.1626 10.2125 13.795 10.5132 13.7538 10.9254L13.2538 15.9254C13.2126 16.3375 13.5133 16.7051 13.9255 16.7463C14.3376 16.7875 14.7051 16.4868 14.7464 16.0746L15.2464 11.0746Z" fill="#1C274C"></path> </g></svg>
                                 </span>

                        </button>
                        <button wire:click="showManagerLeads({{ $manager->id }})"
                                class="text-blue-600 hover:text-blue-800">
                            Показать заявки
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">Менеджеров не найдено</td>
                </tr>
            @endforelse
            </tbody>
        </table>
        @if($selectedManager)
            <div class="mt-8">
                <h3 class="text-xl font-semibold mb-4">
                    Заявки менеджера: {{ $selectedManager->name }}
                </h3>

                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Телефон</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Статус</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Описание</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Дата создания</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($managerLeads as $lead)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $lead->phone }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                    <span class="px-2 py-1 text-xs rounded-full"
                                          style="background-color: {{ $lead->status->color ?? '#e5e7eb' }}">
                                        {{ $lead->status->name ?? 'Нет статуса' }}
                                    </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $lead->description }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $lead->created_at->format('d.m.Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                Нет назначенных заявок
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
