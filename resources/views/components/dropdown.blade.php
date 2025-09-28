@props(['align' => 'right', 'width' => '48', 'contentClasses' => 'p-1 bg-white'])

<div class="dropdown">
    <div data-bs-toggle="dropdown" aria-expanded="false">
        {{ $trigger }}
    </div>

    <div class="dropdown-menu {{ $contentClasses }}">
        {{ $content }}
    </div>
</div>
