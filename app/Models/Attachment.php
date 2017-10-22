<?php
/**
 * Attachment.php
 * Copyright (c) 2017 thegrumpydictator@gmail.com
 *
 * This file is part of Firefly III.
 *
 * Firefly III is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Firefly III is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Firefly III.  If not, see <http://www.gnu.org/licenses/>.
 */

declare(strict_types=1);

namespace FireflyIII\Models;

use Crypt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class Attachment
 *
 * @package FireflyIII\Models
 */
class Attachment extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts
        = [
            'created_at' => 'date',
            'updated_at' => 'date',
            'deleted_at' => 'date',
            'uploaded'   => 'boolean',
        ];
    /** @var array */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    /** @var array */
    protected $fillable = ['attachable_id', 'attachable_type', 'user_id', 'md5', 'filename', 'mime', 'title', 'notes', 'description', 'size', 'uploaded'];

    /**
     * @param Attachment $value
     *
     * @return Attachment
     */
    public static function routeBinder(Attachment $value)
    {
        if (auth()->check()) {

            if (intval($value->user_id) === auth()->user()->id) {
                return $value;
            }
        }
        throw new NotFoundHttpException;
    }

    /**
     * Get all of the owning imageable models.
     *
     * @return MorphTo
     */
    public function attachable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Returns the expected filename for this attachment.
     *
     * @return string
     */
    public function fileName(): string
    {
        return sprintf('at-%s.data', strval($this->id));
    }

    /**
     * @param $value
     *
     * @return null|string
     */
    public function getDescriptionAttribute($value)
    {
        if (is_null($value) || strlen($value) === 0) {
            return null;
        }

        return Crypt::decrypt($value);
    }

    /**
     * @param $value
     *
     * @return null|string
     */
    public function getFilenameAttribute($value)
    {
        if (is_null($value) || strlen($value) === 0) {
            return null;
        }

        return Crypt::decrypt($value);
    }

    /**
     * @param $value
     *
     * @return null|string
     */
    public function getMimeAttribute($value)
    {
        if (is_null($value) || strlen($value) === 0) {
            return null;
        }

        return Crypt::decrypt($value);
    }

    /**
     *
     * @param $value
     *
     * @return null|string
     */
    public function getNotesAttribute($value)
    {
        if (is_null($value) || strlen($value) === 0) {
            return null;
        }

        return Crypt::decrypt($value);
    }

    /**
     *
     * @param $value
     *
     * @return null|string
     */
    public function getTitleAttribute($value)
    {
        if (is_null($value) || strlen($value) === 0) {
            return null;
        }

        return Crypt::decrypt($value);
    }

    /**
     * @param string $value
     */
    public function setDescriptionAttribute(string $value)
    {
        $this->attributes['description'] = Crypt::encrypt($value);
    }

    /**
     * @param string $value
     */
    public function setFilenameAttribute(string $value)
    {
        $this->attributes['filename'] = Crypt::encrypt($value);
    }

    /**
     * @param string $value
     */
    public function setMimeAttribute(string $value)
    {
        $this->attributes['mime'] = Crypt::encrypt($value);
    }

    /**
     * @param string $value
     */
    public function setNotesAttribute(string $value)
    {
        $this->attributes['notes'] = Crypt::encrypt($value);
    }

    /**
     * @param string $value
     */
    public function setTitleAttribute(string $value)
    {
        $this->attributes['title'] = Crypt::encrypt($value);
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo('FireflyIII\User');
    }

}
