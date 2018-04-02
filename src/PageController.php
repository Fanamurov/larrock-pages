<?php

namespace Larrock\ComponentPages;

use Cache;
use LarrockPages;
use App\Http\Controllers\Controller;

class PageController extends Controller
{
    public function __construct()
    {
        LarrockPages::shareConfig();
        $this->middleware(LarrockPages::combineFrontMiddlewares());
    }

    public function getItem($url)
    {
        $data['data'] = Cache::rememberForever('page'.$url, function () use ($url) {
            return LarrockPages::getModel()->whereUrl($url)->with(['getSeo', 'getImages', 'getFiles'])->active()->firstOrFail();
        });

        return view()->first([config('larrock.views.pages.itemUniq.'.$url, 'larrock::front.pages.'.$url),
            config('larrock.views.pages.item', 'larrock::front.pages.item'), ], $data);
    }
}
