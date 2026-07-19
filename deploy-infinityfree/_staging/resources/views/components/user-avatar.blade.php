@props(['user', 'size' => 'sm', 'placeholderBg' => 'bg-[#2F4538]'])

@php
$sizes = [
    'sm' => ['box' => 'w-6 h-6', 'text' => 'text-xs'],
    'md' => ['box' => 'w-12 h-12', 'text' => 'text-lg'],
    'lg' => ['box' => 'w-20 h-20', 'text' => 'text-2xl'],
];
$s = $sizes[$size] ?? $sizes['sm'];
$avatarUrl = $user->avatar ? asset('storage/' . $user->avatar) . '?v=' . $user->updated_at->timestamp : null;
$initial = strtoupper(substr($user->name, 0, 1));
@endphp

@if($avatarUrl)
<img src="{{ $avatarUrl }}"
     alt="Avatar {{ $user->name }}"
     class="{{ $s['box'] }} rounded-full object-cover {{ $attributes->get('class') }}" />
@else
<div class="{{ $s['box'] }} rounded-full {{ $placeholderBg }} flex items-center justify-center text-white {{ $s['text'] }} font-bold flex-shrink-0 {{ $attributes->get('class') }}">
    {{ $initial }}
</div>
@endif
