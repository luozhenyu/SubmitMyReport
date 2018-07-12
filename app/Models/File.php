<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\File
 *
 * @property-read \App\Models\Conversion $conversion
 * @property-read \App\Models\User $owner
 * @mixin \Eloquent
 * @property int $id
 * @property string $random
 * @property string $sha512
 * @property int $size
 * @property string $filename
 * @property int $owner_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\File whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\File whereFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\File whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\File whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\File whereRandom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\File whereSha512($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\File whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\File whereUpdatedAt($value)
 */
class File extends Model
{
    const STORAGE_DIR = 'uploads';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'random', 'sha512', 'size', 'filename',
    ];

    protected $visible = [
        'random', 'size', 'filename',
    ];

    public static function hashToPath(string $sha512)
    {
        return static::STORAGE_DIR
            . DIRECTORY_SEPARATOR . substr($sha512, 0, 2)
            . DIRECTORY_SEPARATOR . substr($sha512, 2, 2);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function conversion()
    {
        return $this->hasOne(Conversion::class, 'random', 'random');
    }

    public function info()
    {
        return [
            'fileName' => $this->filename,
            'fileSize' => static::human_filesize($this->size),
            'url' => "/file/{$this->random}",
            'previewUrl' => "/preview/{$this->random}",
            'random' => $this->random,
        ];
    }

    public static function human_filesize($bytes, $decimals = 2)
    {
        $size = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
    }
}
