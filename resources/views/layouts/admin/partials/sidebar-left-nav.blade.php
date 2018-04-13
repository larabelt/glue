{{--@php--}}
    {{--$can['categories'] = $auth->can(['create','update','delete'], Belt\Glue\Category::class);--}}
    {{--$can['tags'] = $auth->can(['create','update','delete'], Belt\Glue\Tag::class);--}}
{{--@endphp--}}

{{--@if($can['categories'] || $can['tags'])--}}
    {{--<li id="glue-admin-sidebar-left" class="treeview">--}}
        {{--<a href="#">--}}
            {{--<i class="fa fa-tint"></i> <span>Glue Admin</span> <i class="fa fa-angle-left pull-right"></i>--}}
        {{--</a>--}}
        {{--<ul class="treeview-menu">--}}
            {{--@if($can['categories'])--}}
                {{--<li id="glue-admin-sidebar-left-categories"><a href="/admin/belt/glue/categories"><i class="fa fa-sitemap"></i> <span>Categories</span></a></li>--}}
            {{--@endif--}}
            {{--@if($can['tags'])--}}
                {{--<li id="glue-admin-sidebar-left-tags"><a href="/admin/belt/glue/tags"><i class="fa fa-tags"></i> <span>Tags</span></a></li>--}}
            {{--@endif--}}
        {{--</ul>--}}
    {{--</li>--}}
{{--@endif--}}