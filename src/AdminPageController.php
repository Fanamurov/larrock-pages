<?php

namespace Larrock\ComponentPages;

use Larrock\Core\AdminController;
use Breadcrumbs;

class AdminPageController extends AdminController
{
	public function __construct()
	{
        $component = new PageComponent();
        $this->config = $component->shareConfig();

        Breadcrumbs::setView('larrock::admin.breadcrumb.breadcrumb');
        Breadcrumbs::register('admin.'. $this->config->name .'.index', function($breadcrumbs){
            $breadcrumbs->push($this->config->title, '/admin/'. $this->config->name);
        });
	}
}
