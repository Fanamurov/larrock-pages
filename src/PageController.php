<?php

namespace Larrock\ComponentPages;

use App\Http\Controllers\Controller;
use Larrock\Core\Helpers\Plugins\RenderGallery;
use Cache;
use Larrock\ComponentPages\Models\Page;
use Larrock\ComponentPages\Facades\LarrockPages;

class PageController extends Controller
{
	public function __construct()
	{
        LarrockPages::shareConfig();
	}

    public function getItem($url)
	{
		$data = Cache::remember('page'. $url, 1440, function() use ($url) {
			$page = LarrockPages::getModel()->whereUrl($url)->with(['get_seo', 'getImages', 'getFiles'])->firstOrFail();
			$renderGallery = new RenderGallery();
			$data['data'] = $renderGallery->renderFilesGallery($renderGallery->renderGallery($page));
		    return $data;
		});

		if(\View::exists('larrock::front.pages.'. $url)){
			return view('larrock::front.pages.'. $url, $data);
		}
        return view('larrock::front.pages.item', $data);
	}
}
