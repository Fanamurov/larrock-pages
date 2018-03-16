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
            $data->description = str_replace('{Карта}', '<script type="text/javascript" charset="utf-8" async src="https://api-maps.yandex.ru/services/constructor/1.0/js/?sid=ybYTL5aXEPhq1L-vh_qnWole5ypJiCvI&amp;width=100%25&amp;height=450&amp;lang=ru_RU&amp;sourceType=constructor&amp;scroll=true"></script>', $data->description_render);
        @endphp
        <div class="page_description">{!! $data->description !!}</div>
    </div>
@endsection