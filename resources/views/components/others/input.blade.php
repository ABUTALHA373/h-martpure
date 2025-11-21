@php
    $userClass = $attributes->get('class', '');

    // Check if any bg-* class is already provided
    preg_match('/\bbg-[\w-]+\b/', $userClass, $matches);
    $bgClass = $matches[0] ?? 'bg-bg-primary';
@endphp

<input
    {{ $attributes->merge([
        'type' => $type ?? 'text',
        'class' =>
            "rounded-md {$bgClass} border border-custom h-9 px-3 py-1.5 text-md
            placeholder:/50 outline-none text-text-primary placeholder-text-secondary
            focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary"
    ]) }}
/>
