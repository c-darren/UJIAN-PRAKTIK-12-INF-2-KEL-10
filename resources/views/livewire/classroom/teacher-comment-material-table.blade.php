<div>
    <div class="flex gap-2 items-baseline mb-2">
        <textarea wire:model="newComment" rows="1"
            placeholder="Comment"
            class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:text-white"></textarea>
        <button wire:click="postComment" class="bg-blue-500 text-white rounded-xl w-36 h-10 flex items-center justify-center font-bold">
            Komentar
        </button>
    </div>
    <div class=" max-h-[90vh] overflow-y-auto scrollbar-style-1 resize-y max-w-[100%]" wire:poll.5s="updateData">
    @foreach($comments as $comment)
        <div
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform scale-90"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-90"
            class="mt-1 text-gray-500 dark:text-white text-sm border-b border-gray-300 dark:border-gray-700 pb-1 mb-1 break-all mr-0.5">
            <span class="font-bold">{{ $comment['user_name'] === auth()->user()->name ? 'Anda' : $comment['user_name'] }}</span>: 
            <span>{{ $comment['response'] }}</span>
            <br>
            <span class="text-xs text-gray-400 ml-2">{{ $comment['created_human'] }}</span>
        </div>
    @endforeach
    </div>
    @if(count($comments) === 0)
        <div class="mt-1 text-gray-500 dark:text-white text-sm">
            Belum ada komentar.
        </div>
    @endif
</div>