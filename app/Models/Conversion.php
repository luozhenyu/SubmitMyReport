<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
