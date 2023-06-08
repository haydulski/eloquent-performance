<x-layout>
    <h1 class="text-2xl font-bold mb-8 text-gray-100 text-center">Users</h1>

    <form class="border-2 border-red-900 w-1/3 ml-auto rounded-md">
        <label for="search" class="sr-only">Search</label>
        <div class="relative rounded-md shadow-sm">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-200 bg-gray-900" fill="none" stroke="currentColor" stroke-linecap="round"
                     stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <input id="search" name="search" value="{{ request('search') }}"
                   class="bg-transparent form-input block w-full pl-10 py-2 sm:text-basic text-gray-200"
                   placeholder="Search..."/>
        </div>
    </form>
    >
    <div class="border-1 border-gray-200">
        <div class="flex text-gray-400 font-semibold px-4">
            <p class="flex-1">User name</p>
            <p class="flex-1">Email</p>
            <p class="flex-1">Company</p>
        </div>
        @foreach($users as $user)
            <div
                class="bg-gray-800 rounded-md p-4 flex gap-2 my-2 text-gray-300 font-semibold border-2 border-gray-600">
                <div class="flex-1">{{$user->first_name}} {{$user->last_name}}</div>
                <div class="flex-1">{{$user->email}} </div>
                <div class="flex-1">{{$user->company->name}}</div>
            </div>
        @endforeach
    </div>
    {{$users->links('vendor.pagination.tailwind')}}

</x-layout>
