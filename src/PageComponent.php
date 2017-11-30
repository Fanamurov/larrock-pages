<?php

namespace Larrock\ComponentPages;

use Cache;
use Larrock\Core\Component;
use Larrock\Core\Helpers\FormBuilder\FormDate;
use Larrock\Core\Helpers\FormBuilder\FormInput;
use Larrock\Core\Helpers\FormBuilder\FormTextarea;
use Larrock\ComponentPages\Facades\LarrockPages;
use Larrock\ComponentPages\Models\Page;

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
        $this->rows['title'] = $row->setValid('max:255|required')->setTypo()->setFillable();

        $row = new FormTextarea('description', 'Текст');
        $this->rows['description'] = $row->setTypo()->setFillable();

        $row = new FormDate('date', 'Дата материала');
        $this->rows['date'] = $row->setTab('other', 'Дата, вес, активность')->setFillable();

        return $this;
    }

    public function renderAdminMenu()
    {
        $count = \Cache::remember('count-data-admin-'. LarrockPages::getName(), 1440, function(){
            return LarrockPages::getModel()->count(['id']);
        });
        if($count > 0){
            $dropdown = LarrockPages::getModel()->whereActive(1)->orderBy('position', 'desc')->get(['id', 'title', 'url']);
            return view('larrock::admin.sectionmenu.types.dropdown', ['count' => $count, 'app' => LarrockPages::getConfig(), 'url' => '/admin/'. LarrockPages::getName(), 'dropdown' => $dropdown]);
        }
        return view('larrock::admin.sectionmenu.types.default', ['app' => LarrockPages::getConfig(), 'url' => '/admin/'. LarrockPages::getName()]);
    }

    public function createSitemap()
    {
        return LarrockPages::getModel()->whereActive(1)->get();
    }

    public function toDashboard()
    {
        return view('larrock::admin.dashboard.pages', ['component' => LarrockPages::getConfig()]);
    }

    public function search($admin = NULL)
    {
        return Cache::remember('search'. $this->name. $admin, 1440, function() use ($admin){
            $data = [];
            if($admin){
                $items = LarrockPages::getModel()->whereActive(1)->get(['id', 'title', 'url']);
            }else{
                $items = LarrockPages::getModel()->whereActive(1)->get(['id', 'title', 'url']);
            }
            foreach ($items as $item){
                $data[$item->id]['id'] = $item->id;
                $data[$item->id]['title'] = $item->title;
                $data[$item->id]['full_url'] = $item->full_url;
                $data[$item->id]['component'] = $this->name;
                $data[$item->id]['category'] = NULL;
            }
            if(count($data) === 0){
                return NULL;
            }
            return $data;
        });
    }
}