<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assignment()
    {
        return $this->belongsTo(Assignment::class, 'assignment_id');
    }

    public function pictures()
    {
        return $this->belongsToMany(Picture::class, 'submission_picture');
    }

    public function files()
    {
        return $this->belongsToMany(File::class, 'submission_file');
    }
}
