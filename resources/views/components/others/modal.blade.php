@php
    $class = $attributes->get('class', 'w-full');

    $bgClass = $matches[0] ?? 'bg-bg-primary';
    
    // Check if overflow class is provided
    $hasOverflow = str_contains($class, 'overflow-');
    $overflowClass = $hasOverflow ? '' : 'overflow-hidden';
@endphp
<div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
    <div
        class="bg-bg-primary rounded-xl shadow-xl {{$class}} {{$overflowClass}} max-w-2xl max-h-[90vh] flex flex-col border border-custom">
        {{$slot}}
    </div>
</div>
