<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<x-partials.head></x-partials.head>
<body class=" text-black  dark:text-white transition-colors duration-300">
{{$slot}}

@livewireScripts

</body>
</html>
