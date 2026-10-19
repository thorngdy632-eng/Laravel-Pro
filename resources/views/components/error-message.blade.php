@props(['messages'])

<div {{ $attributes->merge(['class' => 'mb-4 rounded-lg border border-red-400 bg-red-100 px-4 py-3 text-red-700']) }}>
    <ul class="list-disc space-y-1 ps-5">
        @foreach ($messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
</div>