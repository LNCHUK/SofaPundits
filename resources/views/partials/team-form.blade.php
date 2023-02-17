<div class="flex">
    @for ($i = 0; $i < strlen($form); $i++)
        @php
            $bgClass = match($form[$i]) {
                'W' => 'bg-emerald-400 text-emerald-800',
                'L' => 'bg-red-400 text-red-900',
                'D' => 'bg-gray-500 text-gray-300',
                default => 'bg-gray-400 text-gray-700 opacity-30'
            };
        @endphp

        <div class="inline-block w-5 h-5 {{ $bgClass }} rounded-full text-[0.6rem] font-bold grid place-content-center mx-0.5">
            {{ $form[$i] }}
        </div>
    @endfor
</div>
