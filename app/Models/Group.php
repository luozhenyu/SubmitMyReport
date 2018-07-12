<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\Group
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Assignment[] $assignments
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $members
 * @property-read \App\Models\User $owner
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $owner_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Group whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Group whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Group whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Group whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Group whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Group whereUpdatedAt($value)
 */
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

    public function orderedAssignments()
    {
        return $this->assignments()
            ->orderByDesc('deadline');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'group_id');
    }

    public function normalMembers()
    {
        return $this->members()
            ->wherePivot('is_admin', false);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function members()
    {
        return $this->belongsToMany(User::class, 'group_user', 'group_id', 'user_id')
            ->withPivot('is_admin')
            ->withTimestamps()
            ->orderByDesc('is_admin')
            ->orderBy('pivot_created_at');
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

