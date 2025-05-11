@props(['class' => ''])

<div style="width: 48px; height: 48px" class="d-flex justify-content-center align-items-center {{ $class }}">
    {{ $slot }}
</div>
