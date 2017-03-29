@php
    $category = $category ?? $owner ?? new \Belt\Glue\Category();
@endphp

<div class="category">

    @if($category->body)
        <div class="category-body">
            {!! $category->body !!}
        </div>
    @endif

    @foreach($category->sections as $section)
        @include($section->template_view, ['section' => $section])
    @endforeach

</div>