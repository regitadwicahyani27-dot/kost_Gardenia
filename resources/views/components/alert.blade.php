@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded-xl mb-4 flex items-center gap-2">
    <span class="w-4 h-4 flex-shrink-0">{!! \App\Support\Icons::get('check-circle') !!}</span>
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="bg-red-100 border border-red-400 text-red-800 px-4 py-3 rounded-xl mb-4 flex items-center gap-2">
    <span class="w-4 h-4 flex-shrink-0">{!! \App\Support\Icons::get('close-circle') !!}</span>
    {{ session('error') }}
</div>
@endif
