<div>
    <form wire:submit.prevent="checkDomains">
        <textarea wire:model="input" rows="5" class="w-full border p-2"></textarea>
        <button class="mt-2 bg-blue-500 text-white px-4 py-2">Проверить</button>
    </form>

    <div class="mt-4">
        @foreach($results as $domain => $status)
            <div>
                <strong>{{ $domain }}</strong>: {{ $status }}
            </div>
        @endforeach
    </div>
</div>
