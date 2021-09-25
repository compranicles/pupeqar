@props(['id' => 'navbarDropdown', 'active', 'nonprofile'])


@php
$classes = ($active ?? false)
            ? 'nav-link active font-weight-bolder'
            : 'nav-link';
@endphp

<li class="nav-item dropdown">
    <a id="{{ $id }}" {!! $attributes->merge(['class' => $classes]) !!} role="button" data-toggle="dropdown" aria-expanded="false">
        {{ $trigger }}
    </a>

    <div class="dropdown-menu animate slideIn" aria-labelledby="{{ $id }}">
        {{ $content }}
    </div>
</li>
