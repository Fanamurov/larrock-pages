<?php

namespace Larrock\ComponentPages\Models;

use Illuminate\Database\Eloquent\Model;
use Larrock\Core\Models\Seo;
use Nicolaslopezj\Searchable\SearchableTrait;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
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

    protected $searchable = [
        'columns' => [
            'page.title' => 10
        ]
    ];

	public function registerMediaConversions()
	{
		$this->addMediaConversion('110x110')
			->setManipulations(['w' => 110, 'h' => 110])
			->performOnCollections('images');

		$this->addMediaConversion('140x140')
			->setManipulations(['w' => 140, 'h' => 140])
			->performOnCollections('images');
	}

    protected $table = 'page';

    protected $fillable = ['title', 'short', 'description', 'url', 'date', 'position', 'active'];

	protected $casts = [
		'position' => 'integer',
		'active' => 'integer'
	];

	protected $dates = ['created_at', 'updated_at', 'date'];

	public function get_seo()
	{
		return $this->hasOne(Seo::class, 'seo_id_connect', 'id')->whereSeoTypeConnect('page');
	}

	public function getImages()
	{
		return $this->hasMany('Spatie\MediaLibrary\Media', 'model_id', 'id')->where([['model_type', '=', LarrockPages::getModelName()], ['collection_name', '=', 'images']])->orderBy('order_column', 'DESC');
	}
	public function getFirstImage()
	{
		return $this->hasOne('Spatie\MediaLibrary\Media', 'model_id', 'id')->where([['model_type', '=', LarrockPages::getModelName()], ['collection_name', '=', 'images']])->orderBy('order_column', 'DESC');
	}

    public function getFiles()
    {
        return $this->hasMany('Spatie\MediaLibrary\Media', 'model_id', 'id')->where([['model_type', '=', LarrockPages::getModelName()], ['collection_name', '=', 'files']])->orderBy('order_column', 'DESC');
    }

	public function getGetSeoTitleAttribute()
	{
		if($get_seo = Seo::whereSeoUrlConnect($this->url)->whereSeoTypeConnect('page')->first()){
			return $get_seo->seo_title;
		}
		if($get_seo = Seo::whereSeoIdConnect($this->id)->whereSeoTypeConnect('page')->first()){
			return $get_seo->seo_title;
		}
		return $this->title;
	}

    public function getFullUrlAttribute()
    {
        return '/page/'. $this->url;
    }
}
