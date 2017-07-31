<?php

namespace Larrock\ComponentPages;

use Larrock\Core\AdminController;
use Breadcrumbs;
use Larrock\ComponentPages\Facades\LarrockPages;

class AdminPageController extends AdminController
{
	public function __construct()
	{
        LarrockPages::shareConfig();

        Breadcrumbs::setView('larrock::admin.breadcrumb.breadcrumb');
        Breadcrumbs::register('admin.'. LarrockPages::getName() .'.index', function($breadcrumbs){
            $breadcrumbs->push(LarrockPages::getTitle(), '/admin/'. LarrockPages::getName());
        });
	}
}
