<?php

namespace Larrock\ComponentPages\Models;

use Illuminate\Database\Eloquent\Model;
use Larrock\Core\Helpers\Plugins\RenderPlugins;
use Larrock\Core\Traits\GetFilesAndImages;
use Larrock\Core\Traits\GetSeo;
use Nicolaslopezj\Searchable\SearchableTrait;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;
use Larrock\ComponentPages\Facades\LarrockPages;

/**
 * Larrock\Models\Page
 *
 * @property integer $id
 * @property string $title
 * @property string $category
 * @property string $short
 * @property string $description
 * @property string $url
 * @property string $date
 * @property string $position
 * @property integer $active
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Larrock\Core\Models\Seo $seo
 * @method static \Illuminate\Database\Query\Builder|\Larrock\ComponentPages\Models\Page whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Larrock\ComponentPages\Models\Page whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\Larrock\ComponentPages\Models\Page whereCategory($value)
 * @method static \Illuminate\Database\Query\Builder|\Larrock\ComponentPages\Models\Page whereShort($value)
 * @method static \Illuminate\Database\Query\Builder|\Larrock\ComponentPages\Models\Page whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\Larrock\ComponentPages\Models\Page whereUrl($value)
 * @method static \Illuminate\Database\Query\Builder|\Larrock\ComponentPages\Models\Page whereDate($value)
 * @method static \Illuminate\Database\Query\Builder|\Larrock\ComponentPages\Models\Page wherePosition($value)
 * @method static \Illuminate\Database\Query\Builder|\Larrock\ComponentPages\Models\Page whereActive($value)
 * @method static \Illuminate\Database\Query\Builder|\Larrock\ComponentPages\Models\Page whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Larrock\ComponentPages\Models\Page whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Larrock\ComponentPages\Models\Page find($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\MediaLibrary\Media[] $media
 * @mixin \Eloquent
 * @property-read mixed $get_seo_title
 * @property-read mixed $full_url
 */
class Page extends Model implements HasMediaConversions
{
    use HasMediaTrait;
    use SearchableTrait;
    use GetFilesAndImages;
    use GetSeo;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->fillable(LarrockPages::addFillableUserRows(['title', 'short', 'description', 'url', 'date', 'position', 'active']));
        $this->table = LarrockPages::getConfig()->table;
        $this->modelName = LarrockPages::getModelName();
        $this->componentName = 'page';
    }

    protected $searchable = [
        'columns' => [
            'page.title' => 10
        ]
    ];

    protected $casts = [
        'position' => 'integer',
        'active' => 'integer'
    ];

    protected $dates = ['created_at', 'updated_at', 'date'];


    public function getFullUrlAttribute()
    {
        return '/page/'. $this->url;
    }

    /**
     * Замена тегов плагинов на их данные
     *
     * @return mixed
     */
    public function getDescriptionRenderAttribute()
    {
        return \Cache::remember('DescriptionRenderPage'. $this->id, 1440, function(){
            $renderPlugins = new RenderPlugins($this->description, $this);
            $render = $renderPlugins->renderBlocks()->renderImageGallery()->renderFilesGallery();
            return $render->rendered_html;
        });
    }
}