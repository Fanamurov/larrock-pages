@extends('larrock::front.main')
@section('title')
    @if($seo_midd['url'])
        {{ $seo_midd['url'] }}
    @else
        {{ $data->get_seo_title }} {{ $seo_midd['postfix_global'] }}
    @endif
@endsection

@section('content')
    <div class="page-{{ $data->url }} uk-position-relative">
        @role('Админ|Модератор')
            <a class="admin_edit" href="/admin/page/{{ $data->id }}/edit">Редактировать</a>
        @endrole
        <h1>{{ $data->title }}</h1>
        <div class="page_description">{!! $data->description_render !!}</div>
    </div>
@endsection