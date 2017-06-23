<?php

namespace Larrock\ComponentPages;

use Larrock\Core\Component;
use Larrock\Core\Helpers\FormBuilder\FormDate;
use Larrock\Core\Helpers\FormBuilder\FormInput;
use Larrock\Core\Helpers\FormBuilder\FormTextarea;
use Larrock\ComponentPages\Models\Page;

class PageComponent extends Component
{
    public function __construct()
    {
        $this->name = $this->table = 'page';
        $this->title = 'Страницы';
        $this->description = 'Страницы без привязки к определенному разделу';
        $this->model = Page::class;
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
        $this->rows['title'] = $row->setValid('max:255|required')->setTypo();

        $row = new FormTextarea('description', 'Текст');
        $this->rows['description'] = $row->setTypo();

        $row = new FormDate('date', 'Дата материала');
        $this->rows['date'] = $row->setTab('other', 'Дата, вес, активность');

        return $this;
    }

    public function renderAdminMenu()
    {
        $count = \Cache::remember('count-data-admin-'. $this->name, 1440, function(){
            return Page::count(['id']);
        });
        $dropdown = Page::whereActive(1)->orderBy('position', 'desc')->get(['id', 'title', 'url']);
        return view('larrock::admin.sectionmenu.types.dropdown', ['count' => $count, 'app' => $this, 'url' => '/admin/'. $this->name, 'dropdown' => $dropdown]);
    }

    public function createSitemap()
    {
        return Page::whereActive(1)->get();
    }
}