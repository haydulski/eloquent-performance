<x-layout>
    <h1 class="text-2xl font-bold mb-8 text-gray-100 text-center">Users statuses</h1>
    <div class="border-1 border-gray-200">
        <div class="flex text-gray-400 font-semibold px-4">
            <p class="flex-1">Online</p>
            <p class="flex-1">Offline</p>
            <p class="flex-1">Banned</p>
        </div>
            <div
                class="bg-gray-800 rounded-md p-4 flex gap-2 my-2 text-gray-300 font-semibold border-2 border-gray-600">
                <div class="flex-1">{{$status['online']}}</div>
                <div class="flex-1">{{$status['offline']}} </div>
                <div class="flex-1">{{$status['banned']}}</div>
            </div>
    </div>

</x-layout>
