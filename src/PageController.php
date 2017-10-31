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
	}

    public function getItem($url)
	{
		$data['data'] = Cache::remember('page'. $url, 1440, function() use ($url) {
			return LarrockPages::getModel()->whereUrl($url)->with(['get_seo', 'getImages', 'getFiles'])->active()->firstOrFail();
		});

		if(\View::exists('larrock::front.pages.'. $url)){
			return view('larrock::front.pages.'. $url, $data);
		}
        return view('larrock::front.pages.item', $data);
	}
}