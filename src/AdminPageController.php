<?php

namespace Larrock\ComponentPages;

use Illuminate\Routing\Controller;
use Breadcrumbs;
use Larrock\ComponentPages\Facades\LarrockPages;
use Larrock\Core\Traits\AdminMethods;

class AdminPageController extends Controller
{
    use AdminMethods;

	public function __construct()
	{
        $this->config = LarrockPages::shareConfig();

        \Config::set('breadcrumbs.view', 'larrock::admin.breadcrumb.breadcrumb');
        Breadcrumbs::register('admin.'. LarrockPages::getName() .'.index', function($breadcrumbs){
            $breadcrumbs->push(LarrockPages::getTitle(), '/admin/'. LarrockPages::getName());
        });
	}
}