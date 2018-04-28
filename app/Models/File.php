<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
            'url' => "/file/{$this->random}",
            'preview_url' => "/preview/{$this->random}",
            'random' => $this->random,
        ];
    }
}
