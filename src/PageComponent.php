<?php

namespace Larrock\ComponentPages;

use Cache;
use LarrockPages;
use Larrock\Core\Component;
use Larrock\ComponentPages\Models\Page;
use Larrock\Core\Helpers\FormBuilder\FormDate;
use Larrock\Core\Helpers\FormBuilder\FormInput;
use Larrock\Core\Helpers\FormBuilder\FormTextarea;

class PageComponent extends Component
{
    public function __construct()
    {
        $this->name = $this->table = 'page';
        $this->title = 'Страницы';
        $this->description = 'Страницы без привязки к определенному разделу';
        $this->model = \config('larrock.models.pages', Page::class);
        $this->addRows()->addPositionAndActive()->isSearchable()->addPlugins();
    }

    protected function addPlugins()
    {
        $this->addPluginImages()->addPluginFiles()->addPluginSeo();

        return $this;
    }

    protected function addRows()
    {
        $row = new FormInput('title', 'Заголовок');
        $this->setRow($row->setValid('max:255|required')->setTypo()->setFillable());

        $row = new FormTextarea('description', 'Текст');
        $this->setRow($row->setTypo()->setFillable());

        $row = new FormDate('date', 'Дата материала');
        $this->setRow($row->setFillable()->setCssClassGroup('uk-width-1-3'));

        return $this;
    }

    public function renderAdminMenu()
    {
        $count = Cache::rememberForever('count-data-admin-'.LarrockPages::getName(), function () {
            return LarrockPages::getModel()->count(['id']);
        });
        if ($count > 0) {
            $dropdown = Cache::rememberForever('dropdownAdminMenu'.LarrockPages::getName(), function () {
                return LarrockPages::getModel()->whereActive(1)->orderBy('position', 'desc')->get(['id', 'title', 'url']);
            });

            return view('larrock::admin.sectionmenu.types.dropdown', ['count' => $count, 'app' => LarrockPages::getConfig(),
                'url' => '/admin/'.LarrockPages::getName(), 'dropdown' => $dropdown, ]);
        }

        return view('larrock::admin.sectionmenu.types.default', ['app' => LarrockPages::getConfig(), 'url' => '/admin/'.LarrockPages::getName()]);
    }

    public function createSitemap()
    {
        return LarrockPages::getModel()->whereActive(1)->get();
    }

    public function toDashboard()
    {
        $data = Cache::rememberForever('LarrockPagesItemsDashboard', function () {
            return LarrockPages::getModel()->latest('updated_at')->take(5)->get();
        });

        return view('larrock::admin.dashboard.pages', ['component' => LarrockPages::getConfig(), 'data' => $data]);
    }
}
