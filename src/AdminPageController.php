<?php

namespace Larrock\ComponentPages;

use Larrock\Core\AdminController;
use Breadcrumbs;
use Larrock\ComponentPages\Facades\LarrockPages;

class AdminPageController extends AdminController
{
	public function __construct()
	{
        $this->config = LarrockPages::shareConfig();

        \Config::set('breadcrumbs.view', 'larrock::admin.breadcrumb.breadcrumb');
        Breadcrumbs::register('admin.'. LarrockPages::getName() .'.index', function($breadcrumbs){
            $breadcrumbs->push(LarrockPages::getTitle(), '/admin/'. LarrockPages::getName());
        });
	}
}
