<div>
    @can('create leads')
    <!-- Поиск и фильтры -->
    <form class="max-w-md mt-2 ml-2">
        <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only">Поиск</label>
        <div class="relative">
            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                </svg>
            </div>
            <input type="search" wire:model.live="searchTerm" class="block w-full p-2.5 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500" placeholder="Поиск по телефону, описанию или источнику..." required />
        </div>
    </form>
    @endcan


    <!-- Форма создания/редактирования -->
    <div class="container mx-auto py-5 ml-2">
        <form class="flex flex-wrap gap-4 my-1.5">
            @can('create leads')
                <div class="flex flex-col">
                    <input type="text" wire:model="phone" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5" placeholder="Телефон">
                    @error('phone') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="flex flex-col">
                    <select wire:model="status_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 pr-8">
                        <option value="">Выберите статус</option>
                        @foreach($statuses as $status)
                            <option value="{{$status->id}}">{{$status->name}}</option>
                        @endforeach
                    </select>
                    @error('status_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="flex flex-col">
                    <textarea wire:model="description" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 pr-8 h-11" placeholder="Описание"></textarea>
                    @error('description') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="flex flex-col">
                    <select wire:model="manager_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 pr-8">
                        <option value="">Не назначен</option>
                        @foreach($managers as $manager)
                            <option value="{{$manager->id}}">{{$manager->name}}</option>
                        @endforeach
                    </select>
                    @error('manager_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="flex flex-col">
                    <input type="text" wire:model="source" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5" placeholder="Источник">
                    @error('source') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                @if($leadForUpdate)
                    <button type="button" wire:click="updateLead" class="text-white bg-pink-300 hover:bg-pink-400 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Обновить</button>
                @else
                    <button type="button" wire:click="storeLead" class="text-white bg-pink-300 hover:bg-pink-400 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Создать</button>
                @endif
            @endcan

            <div>
                <select wire:model.live="perPage" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 pr-8">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="15">15</option>
                    <option value="20">20</option>
                </select>
            </div>

            <div>
                <select wire:model.live="orderDirection" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 pr-8">
                    <option value="desc">По убыванию</option>
                    <option value="asc">По возрастанию</option>
                </select>
            </div>

            <div>
                <select wire:model.live="orderBy" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 pr-8">
                    <option value="created_at">Дата создания</option>
                    <option value="phone">Телефон</option>
                    <option value="source">Источник</option>
                </select>
            </div>
        </form>
    </div>

    <!-- Основная таблица заявок -->
    <div class="bg-white ml-2">
        <div class="overflow-scroll px-0">
            <table class="w-full min-w-max table-auto text-left">
                <thead>
                <tr>
                    <th class="border-y border-blue-gray-100 bg-blue-gray-50/50 p-4">
                        <p class="block antialiased font-sans text-sm text-blue-gray-900 font-normal leading-none opacity-70">Телефон</p>
                    </th>
                    <th class="border-y border-blue-gray-100 bg-blue-gray-50/50 p-4">
                        <p class="block antialiased font-sans text-sm text-blue-gray-900 font-normal leading-none opacity-70">Статус</p>
                    </th>
                    <th class="border-y border-blue-gray-100 bg-blue-gray-50/50 p-4">
                        <p class="block antialiased font-sans text-sm text-blue-gray-900 font-normal leading-none opacity-70">Источник</p>
                    </th>
                    <th class="border-y border-blue-gray-100 bg-blue-gray-50/50 p-4">
                        <p class="block antialiased font-sans text-sm text-blue-gray-900 font-normal leading-none opacity-70">Описание</p>
                    </th>
                    <th class="border-y border-blue-gray-100 bg-blue-gray-50/50 p-4">
                        <p class="block antialiased font-sans text-sm text-blue-gray-900 font-normal leading-none opacity-70">Менеджер</p>
                    </th>
                    @canany(['edit leads', 'delete leads', 'restore leads'])
                        <th class="border-y border-blue-gray-100 bg-blue-gray-50/50 p-4">
                            <p class="block antialiased font-sans text-sm text-blue-gray-900 font-normal leading-none opacity-70">Действия</p>
                        </th>
                    @endcanany
                </tr>
                </thead>
                <tbody>
                @forelse($leads as $lead)
                    <tr>
                        <td class="p-4 border-b border-blue-gray-50">
                            <p class="block antialiased font-sans text-sm leading-normal text-blue-gray-900 font-bold">{{ $lead->phone }}</p>
                        </td>
                        <td class="p-4 border-b border-blue-gray-50">
                        <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium
                            @if($lead->status->name === 'Не обработан') bg-gray-100 text-gray-800
                            @elseif($lead->status->name === 'Ликвид') bg-green-100 text-green-800
                            @elseif($lead->status->name === 'Брак') bg-red-100 text-red-800

                            @else bg-purple-100 text-purple-800 @endif">
                            {{ $lead->status->name }}
                        </span>
                        </td>
                        <td class="p-4 border-b border-blue-gray-50">
                            <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium
                                @if($lead->source === 'website') bg-green-100 text-green-800
                                @elseif($lead->source === 'facebook') bg-blue-100 text-blue-800
                                @elseif($lead->source === 'instagram') bg-purple-100 text-purple-800
                                @elseif($lead->source === 'google') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ $lead->source ?? 'Не указан' }}
                            </span>
                        </td>
                        <td class="p-4 border-b border-blue-gray-50">
                            <p class="block antialiased font-sans text-sm leading-normal text-blue-gray-900 font-normal">{{ $lead->description ?? '-' }}</p>
                        </td>
                        <td class="p-4 border-b border-blue-gray-50">
                            <p class="block antialiased font-sans text-sm leading-normal text-blue-gray-900 font-normal">
                                @if($lead->manager)
                                    {{ $lead->manager->name }}
                                @else
                                    Не назначен

                                @endif
                            </p>
                        </td>
                        @canany(['edit leads', 'delete leads', 'restore leads'])
                            <td class="p-4 border-b border-blue-gray-50">
                                @if($lead->deleted_at)
                                    @can('edit leads')
                                        <button wire:click="restoreLead({{$lead->id}})"
                                                class="relative align-middle select-none font-sans font-medium text-center uppercase transition-all disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none w-10 max-w-[40px] h-10 max-h-[40px] rounded-lg text-xs text-gray-900 hover:bg-gray-900/10 active:bg-gray-900/20" type="button">
                                        <span class="absolute top-1/2 left-1/2 transform -translate-y-1/2 -translate-x-1/2">
                                        <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M4.52185 7H7C7.55229 7 8 7.44772 8 8C8 8.55229 7.55228 9 7 9H3C1.89543 9 1 8.10457 1 7V3C1 2.44772 1.44772 2 2 2C2.55228 2 3 2.44772 3 3V5.6754C4.26953 3.8688 6.06062 2.47676 8.14852 1.69631C10.6633 0.756291 13.435 0.768419 15.9415 1.73041C18.448 2.69239 20.5161 4.53782 21.7562 6.91897C22.9963 9.30013 23.3228 12.0526 22.6741 14.6578C22.0254 17.263 20.4464 19.541 18.2345 21.0626C16.0226 22.5842 13.3306 23.2444 10.6657 22.9188C8.00083 22.5931 5.54702 21.3041 3.76664 19.2946C2.20818 17.5356 1.25993 15.3309 1.04625 13.0078C0.995657 12.4579 1.45216 12.0088 2.00445 12.0084C2.55673 12.0079 3.00351 12.4566 3.06526 13.0055C3.27138 14.8374 4.03712 16.5706 5.27027 17.9625C6.7255 19.605 8.73118 20.6586 10.9094 20.9247C13.0876 21.1909 15.288 20.6513 17.0959 19.4075C18.9039 18.1638 20.1945 16.3018 20.7247 14.1724C21.2549 12.043 20.9881 9.79319 19.9745 7.8469C18.9608 5.90061 17.2704 4.3922 15.2217 3.6059C13.173 2.8196 10.9074 2.80968 8.8519 3.57803C7.11008 4.22911 5.62099 5.40094 4.57993 6.92229C4.56156 6.94914 4.54217 6.97505 4.52185 7Z" fill="#0F0F0F"></path> </g></svg>
                                    </span>
                                        </button>
                                    @endcan
                                @else
                                    @can('edit leads')
                                        <button wire:click="getUpdateLead({{ $lead->id }})" class="text-blue-600 hover:text-blue-800 mr-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                            </svg>
                                        </button>
                                    @endcan

                                    @can('delete leads')
                                        <button wire:click="deleteLead({{ $lead->id }})" class="text-red-600 hover:text-red-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    @endcan
                                @endif
                            </td>
                        @endcanany
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-4 text-center">Нет данных</td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            {{ $leads->links() }}
        </div>
    </div>
</div>
