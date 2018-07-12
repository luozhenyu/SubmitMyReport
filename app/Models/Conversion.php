<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Conversion
 *
 * @property-read string $readable_status
 * @mixin \Eloquent
 * @property int $id
 * @property string $random
 * @property int $status
 * @property string|null $log
 * @property string|null $finished_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Conversion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Conversion whereFinishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Conversion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Conversion whereLog($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Conversion whereRandom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Conversion whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Conversion whereUpdatedAt($value)
 */
class Conversion extends Model
{
    const IN_QUEUE = 0;
    const PROCESSING = 1;
    const SUCCESS = 2;
    const FAIL = 3;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sha512', 'status', 'finished_at'
    ];

    /**
     * @return string
     */
    public function getReadableStatusAttribute()
    {
        $status_string = [
            Conversion::IN_QUEUE => '队列中',
            Conversion::PROCESSING => '转换中',
            Conversion::SUCCESS => '转换成功',
            Conversion::FAIL => '转换失败',
        ];

        return $status_string[$this->status];
    }

}
