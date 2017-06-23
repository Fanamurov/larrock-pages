@extends('front.main')
@section('title') {{ $data->get_seo_title }} @endsection

@section('content')
    <div class="page-{{ $data->url }}">
        @role('Админ|Модератор')
            <a class="admin_edit" href="/admin/page/{{ $data->id }}/edit">Редактировать</a>
        @endrole
        <h1>{{ $data->title }}</h1>
        <div class="page_description">{!! $data->description !!}</div>
    </div>
@endsection