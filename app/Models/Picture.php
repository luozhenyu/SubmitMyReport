<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Assignment;
use App\Models\Submission;

class Picture extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'extension'
    ];

    public function Assignment()
    {
        return $this->belongTo(Assignment::class,'assignment_id');
    }

    public function Submission()
    {
        return $this->belongTo(Submission::class,'submission_id');
    }
}
