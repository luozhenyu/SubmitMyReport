<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

/**
 * App\Models\Assignment
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\File[] $files
 * @property-read mixed $human_deadline
 * @property-read \App\Models\Group $group
 * @property-read \App\Models\User $owner
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Submission[] $submissions
 * @mixin \Eloquent
 * @property int $id
 * @property string $title
 * @property int $sub_problem
 * @property string $deadline
 * @property string $description
 * @property int $owner_id
 * @property int $group_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Assignment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Assignment whereDeadline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Assignment whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Assignment whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Assignment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Assignment whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Assignment whereSubProblem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Assignment whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Assignment whereUpdatedAt($value)
 */
class Assignment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'sub_problem', 'deadline', 'description', 'owner_id'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function scoredSubmissions()
    {
        return $this->submissions()->whereHas('mark');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function submissions()
    {
        return $this->hasMany(Submission::class, 'assignment_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function myScoredSubmissions()
    {
        return $this->submissions()->whereHas('mark', function (Builder $query) {
            $query->where('owner_id', Auth::user()->id);
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function files()
    {
        return $this->belongsToMany(File::class, 'assignment_file');
    }

    public function loginSubmissions($logined = null)
    {
        $logined = $logined ?? Auth::user();
        return $this->submissions()
            ->where('owner_id', $logined->id);
    }

    public function getHumanDeadlineAttribute()
    {
        $date = Carbon::createFromTimeString($this->deadline);

        if ($date->year === Carbon::now()->year) {
            return $date->format("m-d H:i");
        }

        return $date->format("Y-m-d H:i");
    }
}
