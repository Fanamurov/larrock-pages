<?php

namespace Larrock\ComponentPages\Models;

use Cache;
use LarrockPages;
use Larrock\Core\Component;
use Larrock\Core\Traits\GetSeo;
use Larrock\Core\Traits\GetLink;
use Spatie\MediaLibrary\Models\Media;
use Illuminate\Database\Eloquent\Model;
use Larrock\Core\Traits\GetFilesAndImages;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Nicolaslopezj\Searchable\SearchableTrait;
use Larrock\Core\Helpers\Plugins\RenderPlugins;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

/**
 * Larrock\ComponentPages\Models\Page.
 *
 * @property int $id
 * @property string $title
 * @property string $category
 * @property string $short
 * @property string $description
 * @property string $url
 * @property string $date
 * @property string $position
 * @property int $active
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property mixed $description_render
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
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\MediaLibrary\Models\Media[] $media
 * @mixin \Eloquent
 * @property-read mixed $get_seo_title
 * @property-read mixed $full_url
 */
class Page extends Model implements HasMedia
{
    /** @var $this Component */
    protected $config;

    use SearchableTrait, GetFilesAndImages, GetSeo, GetLink;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->fillable(LarrockPages::addFillableUserRows([]));
        $this->config = LarrockPages::getConfig();
        $this->table = LarrockPages::getTable();
    }

    protected $searchable = [
        'columns' => [
            'page.title' => 10,
        ],
    ];

    protected $casts = [
        'position' => 'integer',
        'active' => 'integer',
    ];

    protected $dates = ['created_at', 'updated_at', 'date'];

    public function getConfig()
    {
        return $this->config;
    }

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    public function getFullUrlAttribute()
    {
        return '/page/'.$this->url;
    }

    /**
     * Замена тегов плагинов на их данные.
     * @return mixed
     * @throws \Throwable
     */
    public function getDescriptionRenderAttribute()
    {
        $cache_key = 'DescriptionRender'.$this->config->table.'-'.$this->id;
        if (\Auth::check()) {
            $cache_key .= '-'.\Auth::user()->role->first()->level;
        }

        return Cache::rememberForever($cache_key, function () {
            $renderPlugins = new RenderPlugins($this->description, $this);
            $render = $renderPlugins->renderBlocks()->renderImageGallery()->renderFilesGallery();

            return $render->rendered_html;
        });
    }

    /**
     * Перезаписываем метод из HasMediaTrait, добавляем кеш.
     * @param string $collectionName
     * @return mixed
     */
    public function loadMedia(string $collectionName)
    {
        $cache_key = sha1('loadMediaCache'.$collectionName.$this->id.$this->getConfig()->getModelName());

        return Cache::rememberForever($cache_key, function () use ($collectionName) {
            $collection = $this->exists
                ? $this->media
                : collect($this->unAttachedMediaLibraryItems)->pluck('media');

            return $collection->filter(function (Media $mediaItem) use ($collectionName) {
                if ($collectionName === '') {
                    return true;
                }

                return $mediaItem->collection_name === $collectionName;
            })->sortBy('order_column')->values();
        });
    }
}
