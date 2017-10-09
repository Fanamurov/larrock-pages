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
        @php
        $data->description = str_replace('{Карта}', '<script type="text/javascript" charset="utf-8" async src="https://api-maps.yandex.ru/services/constructor/1.0/js/?um=constructor%3A4019a195e6fb892f61459aa21e155777bb71b770cb0810019ea7d8e16a27f840&amp;width=100%25&amp;height=400&amp;lang=ru_RU&amp;scroll=true"></script>', $data->description);
        @endphp
        <div class="page_description">{!! $data->description !!}</div>
    </div>
@endsection