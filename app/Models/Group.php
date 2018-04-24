<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'group_id');
    }

    public function orderedAssignments()
    {
        return $this->assignments()
            ->orderByDesc('deadline');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function members()
    {
        return $this->belongsToMany(User::class, 'group_user', 'group_id', 'user_id')
            ->withPivot('is_admin')
            ->withTimestamps()
            ->orderBy('pivot_created_at');
    }

    public function normalMembers()
    {
        return $this->members()
            ->wherePivot('is_admin', false);
    }

    public function admins()
    {
        return $this->members()
            ->wherePivot('is_admin', true);
    }

    /**
     * 登录用户是否管理员
     * @return bool
     */
    public function loginAdmin()
    {
        return (boolean)Auth::user()->managedGroups()->find($this->id);
    }

    /**
     * 登录用户是否加入
     * @return bool
     */
    public function loginJoined()
    {
        return (boolean)Auth::user()->joinedGroups()->find($this->id);
    }

    public function loginNotSubmit()
    {
        return $this->assignments()->whereNotExists(function (Builder $query) {
            $query->select(DB::raw(1))
                ->from('submissions')
                ->where('owner_id', Auth::user()->id)
                ->whereRaw('submissions.assignment_id = assignments.id');
        });
    }
}

