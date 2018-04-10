<?php namespace Datlv\Image\Support;

use Datlv\Image\Image;

/**
 * Dùng cho Model có nhiều images (vd: Gallery... )
 * Trait Imageable
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Datlv\Image\Image[] $images
 * @package Datlv\Image\Support
 * @mixin \Eloquent
 */
trait Imageable
{
    public static function bootImageable()
    {
        static::deleting(
            function ($model) {
                /** @var \Datlv\Image\Support\Imageable|static $model */
                $model->images()->detach();
            }
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function images()
    {
        return $this->morphToMany(Image::class, 'imageable')->orderBy('imageables.position');
    }

    /**
     * @return \Datlv\Image\Image
     */
    public function firstImage()
    {
        return $this->images->first();
    }
}