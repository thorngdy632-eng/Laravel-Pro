@props(['message'])

<div {{ $attributes->merge(['class' => 'mb-4 rounded-lg border border-green-400 bg-green-100 px-4 py-3 text-green-700']) }}>
    {{ $message }}
</div>