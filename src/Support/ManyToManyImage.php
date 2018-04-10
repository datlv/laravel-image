<?php namespace Datlv\Image\Support;

use Datlv\Image\Image;

/**
 * Trait ManyToManyImage
 * $additionalImageData: Các trường thông tin thêm trong pivot table
 * $positionImage: Có quân tâm thứ tự image, có 'position' trong pivot table
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Datlv\Image\Image[] $images
 * @property array $additionalImageData
 * @property bool $positionableImage
 * @package Datlv\Image\Support
 * @mixin \Eloquent
 */
trait ManyToManyImage
{
    public static function bootImageable()
    {
        static::deleting(
            function ($model) {
                /** @var \Datlv\Image\Support\ManyToManyImage|static $model */
                $model->images()->detach();
            }
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function images()
    {
        $relation = $this->belongsToMany(Image::class);
        if ($this->additionalImageData) {
            $relation->withPivot($this->additionalImageData);
        }

        if ($this->positionableImage) {
            $relation->orderBy("{$this->joiningTable(Image::class)}.position");
        }

        return $relation;
    }

    /**
     * @return \Datlv\Image\Image|null
     */
    public function firstImage()
    {
        return $this->images->first();
    }

    /**
     * Danh sách images, chỉ lấy các $attributes
     * 'meta' chứa dữ liệu 'Additional Image Data'
     *
     * @param array $attributes
     * @return array
     */
    public function arrayImages($attributes = ['id', 'url' => 'src', 'thumb'])
    {
        $images = [];
        foreach ($this->images as $image) {
            $data = $image->arrayAttributes($attributes);
            if ($this->additionalImageData) {
                $data['meta'] = [];
                foreach ($this->additionalImageData as $key) {
                    $data['meta'][$key] = $image->pivot->{$key};
                }
            }
            $images[] = $data;
        }

        return $images;
    }

    /**
     * @param array $images
     */
    public function syncImages($images = [])
    {
        if ($this->positionableImage) {
            $i = 0;
            foreach ($images as &$image) {
                $image['position'] = $i++;
            }
        }
        $this->images()->sync($images);
    }
}