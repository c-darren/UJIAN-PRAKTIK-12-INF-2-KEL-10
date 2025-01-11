<div wire:poll.10s>
    @if($submission && $submission->feedback)
        @foreach(json_decode($submission->feedback, true) ?? [] as $index => $feedback)
        @php
            $feedbackUser = $feedbackUsers[$feedback['user_id']] ?? null;
        @endphp
        <div class="feedback-item bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
            <div class="flex justify-between items-start">
                <div class="text-sm text-gray-900 dark:text-white">
                    @if($feedbackUser->name == Auth::user()->name)
                        <span class="font-semibold">Anda</span>
                    @else
                        <span class="font-semibold">{{ $feedbackUser->name }}</span>
                    @endif
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        {{ $feedback['timestamp'] ?? '-' }}
                    </div>
                </div>
                @if(auth()->id() == $feedback['user_id'])
                <button @click="deleteFeedback({{ $submission->id }}, {{ $index }})"
                        class="text-red-500 hover:text-red-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                @endif
            </div>
            <div class="text-sm text-gray-900 dark:text-white mt-2">
                {{ $feedback['content'] }}
            </div>
        </div>
        @endforeach
    @endif
</div>