<?php

namespace Larrock\ComponentPages;

use Illuminate\Routing\Controller;
use Larrock\ComponentPages\Facades\LarrockPages;
use Larrock\Core\Traits\AdminMethods;

class AdminPageController extends Controller
{
    use AdminMethods;

	public function __construct()
	{
        $this->middleware(LarrockPages::combineAdminMiddlewares());
        $this->config = LarrockPages::shareConfig();
        \Config::set('breadcrumbs.view', 'larrock::admin.breadcrumb.breadcrumb');
	}
}