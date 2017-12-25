<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'group_id');
    }

    public function member()
    {
        return $this->belongsToMany(User::class, 'group_user', 'group_id', 'user_id')->withPivot('is_admin');
    }

    public function admin()
    {
        return $this->belongsToMany(User::class, 'group_user', 'group_id', 'user_id')->withPivot('is_admin')->wherePivot('is_admin', '=', 1);
    }
}

