@if (session()->has('success'))
    <div class="bg-emerald-100 px-6 py-4 rounded-lg border border-emerald-200 mb-8 drop-shadow text-emerald-700">
        {{ session()->get('success') }}
    </div>
@endif

@if (session()->has('error'))
    <div class="bg-red-100 px-6 py-4 rounded-lg border border-red-200 mb-8 drop-shadow text-red-700">
        {{ session()->get('error') }}
    </div>
@endif