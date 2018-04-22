<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'student_id', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function createdGroups()
    {
        return $this->hasMany(Group::class, 'owner_id');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'owner_id');
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class, 'owner_id');
    }

    public function joinedGroups()
    {
        return $this->belongsToMany(Group::class, 'group_user', 'user_id', 'group_id')
            ->withPivot('is_admin')
            ->withTimestamps();
    }

    public function managedGroups()
    {
        return $this->joinedGroups()
            ->wherePivot('is_admin', true);
    }
}