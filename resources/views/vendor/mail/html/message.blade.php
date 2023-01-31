<x-mail::layout>
@isset($preheader)
<x-slot:preheader>
{{ $preheader }}
</x-slot:preheader>
@endisset

{{-- Header --}}
<x-slot:header>
<x-mail::header :url="config('app.url')">
<img src="{{ url('images/logo.svg') }}" class="logo" alt="Sofa Pundits">
</x-mail::header>
</x-slot:header>

{{-- Body --}}
{{ $slot }}

{{-- Subcopy --}}
@isset($subcopy)
<x-slot:subcopy>
<x-mail::subcopy>
<div style="font-size: 0.75em; color: #9ea5ab; text-align: center;" align="center">
{{ $subcopy }}
</div>
</x-mail::subcopy>
</x-slot:subcopy>
@endisset

{{-- Footer --}}
<x-slot:footer>
<x-mail::footer>
© {{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.')
</x-mail::footer>
</x-slot:footer>
</x-mail::layout>
