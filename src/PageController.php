<?php

namespace Larrock\ComponentPages;

use App\Http\Controllers\Controller;
use Cache;
use Larrock\ComponentPages\Facades\LarrockPages;

class PageController extends Controller
{
	public function __construct()
	{
        LarrockPages::shareConfig();
        $this->middleware(LarrockPages::combineFrontMiddlewares());
	}

    public function getItem($url)
	{
		$data['data'] = Cache::remember('page'. $url, 1440, function() use ($url) {
			return LarrockPages::getModel()->whereUrl($url)->with(['get_seo', 'getImages', 'getFiles'])->active()->firstOrFail();
		});
		return view()->first([config('larrock.views.pages.itemUniq.'. $url, 'larrock::front.pages.'. $url),
            config('larrock.views.pages.item', 'larrock::front.pages.item')], $data);
	}
}