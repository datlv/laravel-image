<?php namespace Datlv\Image;

use Datlv\Kit\Extensions\ModelTransformer;
use Html;

/**
 * Class ImageTransformer
 *
 * @package Datlv\Image
 */
class ImageTransformer extends ModelTransformer
{
    /**
     * @param \Datlv\Image\Image $image
     *
     * @return array
     */
    public function transform(Image $image)
    {
        return [
            'id'      => (int)$image->id,
            'title'   => $image->present()->block,
            'updated_at'   => $image->present()->updatedAt(['template' => ':date<br>:time']),
            'width'   => $image->width,
            'height'  => $image->height,
            'mime'    => $image->present()->mime,
            'size'    => $image->present()->size,
            'used'    => $image->used,
            'actions' => Html::tableActions(
                'backend.image',
                ['image' => $image->id],
                trans('image::common.images') . ($image->title ? ": {$image->title}" : ''),
                trans('image::common.images'),
                [
                    'renderShow'   => 'modal-large',
                    'renderDelete' => (int)$image->used ? 'disabled' : 'link',
                    'titleEdit'    => trans('image::common.replace'),
                ]
            ),
        ];
    }
}