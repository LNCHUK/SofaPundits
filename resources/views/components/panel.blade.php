<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg @if($attributes['fullHeight']) h-full @endif">
    <div class="p-6 bg-white border-b border-gray-200 @if($attributes['fullHeight']) h-full @endif">
        {{ $slot }}
    </div>
</div>