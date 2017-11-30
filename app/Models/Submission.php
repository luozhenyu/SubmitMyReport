<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Assignment;

class Submission extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'content'
    ];

    public function User()
    {
        return $this->belongTo(User::class,'user_id');
    }

    public function Assignment()
    {
        return $this->belongTo(Assignment::class,'assignment_id');
    }
}
