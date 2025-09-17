<div class="p-4">
    <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-2xl font-semibold mb-4">Управление партнерами</h2>

        <!-- Форма -->
        <form wire:submit.prevent="{{ $partnerId ? 'update' : 'create' }}" class="mb-4">
            <div class="flex flex-wrap items-end gap-3">
                <div class="flex-1 min-w-[150px]">
                    <label for="name" class="block text-sm font-medium text-gray-700">Имя</label>
                    <input wire:model="name" type="text" id="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 h-10">
                    @error('name') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="flex-1 min-w-[150px]">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input wire:model="email" type="email" id="email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 h-10">
                    @error('email') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="flex-1 min-w-[150px]">
                    <label for="company" class="block text-sm font-medium text-gray-700">Компания</label>
                    <input wire:model="company" type="text" id="company" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 h-10">
                    @error('company') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-end gap-2 h-10">
                    @if($partnerId)
                        <button type="button" wire:click="resetForm"
                                class="px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 h-full">
                            Отмена
                        </button>
                    @endif
                    <button type="submit"
                            class="text-white bg-pink-300 hover:bg-pink-400 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">
                        {{ $partnerId ? 'Обновить' : 'Создать' }}
                    </button>
                </div>
                <div>
                    <select wire:model.live="perPage" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 pr-8">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="15">15</option>
                        <option value="20">20</option>
                    </select>
                </div>
                <div>
                    <select wire:model.live="orderBy" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 pr-8">
                        <option value="name">По имени</option>
                        <option value="email">По почте</option>
                        <option value="created_at">По дате создания</option>
                        <option value="deleted_at">По дате удаления</option>
                    </select>
                </div>
                <div>
                    <select wire:model.live="orderDirection" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 pr-8">
                        <option value="desc">По убыванию</option>
                        <option value="asc">По возрастанию</option>
                    </select>
                </div>
            </div>
        </form>



        <!-- Таблица -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" wire:click="orderBy('name')">
                        Имя
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Email
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" wire:click="orderBy('company')">
                        Компания
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Действия
                    </th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @forelse($partners as $partner)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $partner->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $partner->email }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $partner->company }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            @if($partner->deleted_at)
                                <button wire:click="restore({{$partner->id}})"
                                        class="relative align-middle select-none font-sans font-medium text-center uppercase transition-all disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none w-10 max-w-[40px] h-10 max-h-[40px] rounded-lg text-xs text-gray-900 hover:bg-gray-900/10 active:bg-gray-900/20" type="button">
                                        <span class="absolute top-1/2 left-1/2 transform -translate-y-1/2 -translate-x-1/2">
                                        <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M4.52185 7H7C7.55229 7 8 7.44772 8 8C8 8.55229 7.55228 9 7 9H3C1.89543 9 1 8.10457 1 7V3C1 2.44772 1.44772 2 2 2C2.55228 2 3 2.44772 3 3V5.6754C4.26953 3.8688 6.06062 2.47676 8.14852 1.69631C10.6633 0.756291 13.435 0.768419 15.9415 1.73041C18.448 2.69239 20.5161 4.53782 21.7562 6.91897C22.9963 9.30013 23.3228 12.0526 22.6741 14.6578C22.0254 17.263 20.4464 19.541 18.2345 21.0626C16.0226 22.5842 13.3306 23.2444 10.6657 22.9188C8.00083 22.5931 5.54702 21.3041 3.76664 19.2946C2.20818 17.5356 1.25993 15.3309 1.04625 13.0078C0.995657 12.4579 1.45216 12.0088 2.00445 12.0084C2.55673 12.0079 3.00351 12.4566 3.06526 13.0055C3.27138 14.8374 4.03712 16.5706 5.27027 17.9625C6.7255 19.605 8.73118 20.6586 10.9094 20.9247C13.0876 21.1909 15.288 20.6513 17.0959 19.4075C18.9039 18.1638 20.1945 16.3018 20.7247 14.1724C21.2549 12.043 20.9881 9.79319 19.9745 7.8469C18.9608 5.90061 17.2704 4.3922 15.2217 3.6059C13.173 2.8196 10.9074 2.80968 8.8519 3.57803C7.11008 4.22911 5.62099 5.40094 4.57993 6.92229C4.56156 6.94914 4.54217 6.97505 4.52185 7Z" fill="#0F0F0F"></path> </g></svg>
                                    </span>
                                </button>
                            @else
                                <button wire:click="edit({{ $partner->id }})" class="text-blue-600 hover:text-blue-900">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                    </svg>
                                </button>
                                <button wire:click="delete({{ $partner->id }})" class="text-red-600 hover:text-red-900">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                            Партнеры не найдены
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $partners->links() }}
        </div>
    </div>
</div>
