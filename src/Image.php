<?php

namespace Datlv\Image;

use Laracasts\Presenter\PresentableTrait;
use Datlv\Kit\Extensions\Model;
use Datlv\Kit\Traits\Model\DatetimeQuery;
use Datlv\Kit\Traits\Model\SearchQuery;
use Datlv\User\Support\HasOwner;
use Datlv\Tag\Taggable;

/**
 * Class ImageModel
 *
 * @package Datlv\Image
 * @property integer $id
 * @property string $title
 * @property string $filename
 * @property integer $width
 * @property integer $height
 * @property string $mime
 * @property integer $size
 * @property integer $used
 * @property integer $user_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read mixed $type
 * @property-read string $src
 * @property-read string $path
 * @property-read string $thumb
 * @property-read string $thumb_path
 * @property-read string $thumb_4x
 * @property-read string $thumb_4x_path
 * @property-read string $small
 * @property-read string $small_path
 * @property-read string $resource_name
 * @property-read \Datlv\User\User $user
 * @method static \Illuminate\Database\Query\Builder|\Datlv\Image\Image whereId( $value )
 * @method static \Illuminate\Database\Query\Builder|\Datlv\Image\Image whereTitle( $value )
 * @method static \Illuminate\Database\Query\Builder|\Datlv\Image\Image whereFilename( $value )
 * @method static \Illuminate\Database\Query\Builder|\Datlv\Image\Image whereWidth( $value )
 * @method static \Illuminate\Database\Query\Builder|\Datlv\Image\Image whereHeight( $value )
 * @method static \Illuminate\Database\Query\Builder|\Datlv\Image\Image whereMime( $value )
 * @method static \Illuminate\Database\Query\Builder|\Datlv\Image\Image whereSize( $value )
 * @method static \Illuminate\Database\Query\Builder|\Datlv\Image\Image whereUsed( $value )
 * @method static \Illuminate\Database\Query\Builder|\Datlv\Image\Image whereUserId( $value )
 * @method static \Illuminate\Database\Query\Builder|\Datlv\Image\Image whereCreatedAt( $value )
 * @method static \Illuminate\Database\Query\Builder|\Datlv\Image\Image whereUpdatedAt( $value )
 * @method static \Illuminate\Database\Query\Builder|\Datlv\Kit\Extensions\Model except( $id = null )
 * @method static \Illuminate\Database\Query\Builder|\Datlv\Image\Image related()
 * @method static \Illuminate\Database\Query\Builder|\Datlv\Image\Image orderByMatchedTag( $tagNames, $direction = 'desc' )
 * @method static \Illuminate\Database\Query\Builder|\Datlv\Image\Image withAllTags( $tagNames )
 * @method static \Illuminate\Database\Query\Builder|\Datlv\Image\Image withAnyTag( $tagNames )
 * @method static \Illuminate\Database\Query\Builder|\Datlv\Image\Image orderCreated( $direction = 'desc' )
 * @method static \Illuminate\Database\Query\Builder|\Datlv\Image\Image orderUpdated( $direction = 'desc' )
 * @method static \Illuminate\Database\Query\Builder|\Datlv\Image\Image period( $start = null, $end = null, $field = 'created_at', $end_if_day = false, $is_month = false )
 * @method static \Illuminate\Database\Query\Builder|\Datlv\Image\Image today( $field = 'created_at' )
 * @method static \Illuminate\Database\Query\Builder|\Datlv\Image\Image yesterday( $same_time = false, $field = 'created_at' )
 * @method static \Illuminate\Database\Query\Builder|\Datlv\Image\Image thisWeek( $field = 'created_at' )
 * @method static \Illuminate\Database\Query\Builder|\Datlv\Image\Image thisMonth( $field = 'created_at' )
 * @method static \Illuminate\Database\Query\Builder|\Datlv\Image\Image notMine()
 * @method static \Illuminate\Database\Query\Builder|\Datlv\Image\Image mine()
 * @method static \Illuminate\Database\Query\Builder|\Datlv\Image\Image withAuthor()
 * @method static \Illuminate\Database\Query\Builder|\Datlv\Image\Image searchWhere( $column, $operator = '=', $fn = null )
 * @method static \Illuminate\Database\Query\Builder|\Datlv\Image\Image searchWhereIn( $column, $fn )
 * @method static \Illuminate\Database\Query\Builder|\Datlv\Image\Image searchWhereBetween( $column, $fn = null )
 * @method static \Illuminate\Database\Query\Builder|\Datlv\Image\Image searchWhereInDependent( $column, $column_dependent, $fn, $empty = [] )
 */
class Image extends Model {
    use Taggable;
    use PresentableTrait;
    use DatetimeQuery;
    use HasOwner;
    use SearchQuery;

    protected $presenter = Presenter::class;
    protected $table = 'images';
    protected $fillable = [ 'title', 'filename', 'width', 'height', 'mime', 'size', 'used', 'user_id', 'tag_names' ];

    /**
     * getter $model->type
     *
     * @return string
     */
    public function getTypeAttribute() {
        return $this->mime ? config( "image.mime_types.{$this->mime}" ) : null;
    }

    /**
     * getter $model->src
     *
     * @return string
     */
    public function getSrcAttribute() {
        return $this->getPath( 'images', false );
    }

    /**
     * getter $model->path
     *
     * @return string
     */
    public function getPathAttribute() {
        return $this->getPath( 'images', true );
    }

    /**
     * getter $model->thumb
     *
     * @return string
     */
    public function getThumbAttribute() {
        return $this->getPath( 'thumbs', false );
    }

    /**
     * getter $model->thumb_path
     *
     * @return string
     */
    public function getThumbPathAttribute() {
        return $this->getPath( 'thumbs', true );
    }

    /**
     * getter $model->thumb_4x
     *
     * @return string
     */
    public function getThumb4xAttribute() {
        return $this->getPath( 'thumbs-4x', false );
    }

    /**
     * getter $model->thumb_4x_path
     *
     * @return string
     */
    public function getThumb4xPathAttribute() {
        return $this->getPath( 'thumbs-4x', true );
    }

    /**
     * getter $model->small
     *
     * @return string
     */
    public function getSmallAttribute() {
        return $this->getPath( 'smalls', false );
    }

    /**
     * getter $model->small_path
     *
     * @return string
     */
    public function getSmallPathAttribute() {
        return $this->getPath( 'smalls', true );
    }

    /**
     * Lấy một số attributes, trả về dạng array
     * vd: $select = ['id', 'tag' => 'tag_names']
     * trả về ['id' => $image->id, 'tag' => $images->tag_names]
     *
     * @param array $select
     *
     * @return array
     */
    public function arrayAttributes( $select = [] ) {
        $array = [];
        foreach ( $select as $key => $attr ) {
            $array[is_numeric( $key ) ? $attr : $key] = $this->present()->$attr;
        }

        return $array;
    }

    /**
     * @param string $of
     * @param bool $full
     *
     * @return null|string
     */
    protected function getPath( $of, $full ) {
        if ( $user = $this->user ) {
            return $user->upload_path( $of, $full ) . '/' . $this->filename;
        } else {
            return null;
        }
    }

    /**
     * Hook các events của model
     *
     * @return void
     */
    public static function boot() {
        parent::boot();
        // trước khi xóa $image trong DB, xóa 2 hình ảnh của nó
        static::deleting(
            function ( Image $model ) {
                @unlink( $model->getPathAttribute() );
                @unlink( $model->getThumbPathAttribute() );
                @unlink( $model->getThumb4xPathAttribute() );
                @unlink( $model->getSmallPathAttribute() );
            }
        );
    }
}