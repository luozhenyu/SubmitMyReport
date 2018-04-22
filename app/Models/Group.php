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
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'group_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'group_user', 'group_id', 'user_id')
            ->withPivot('is_admin')
            ->withTimestamps();
    }

    public function normalUsers()
    {
        return $this->members()
            ->wherePivot('is_admin', false);
    }

    public function admins()
    {
        return $this->members()
            ->wherePivot('is_admin', true);
    }
}

