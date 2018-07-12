<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Submission
 *
 * @property-read \App\Models\Assignment $assignment
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\File[] $files
 * @property-read \App\Models\Mark $mark
 * @property-read \App\Models\User $owner
 * @mixin \Eloquent
 * @property int $id
 * @property string|null $content
 * @property int $owner_id
 * @property int $assignment_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Submission whereAssignmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Submission whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Submission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Submission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Submission whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Submission whereUpdatedAt($value)
 */
class Submission extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'content', 'owner_id',
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
    public function assignment()
    {
        return $this->belongsTo(Assignment::class, 'assignment_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function files()
    {
        return $this->belongsToMany(File::class, 'submission_file');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function mark()
    {
        return $this->hasOne(Mark::class, 'submission_id');
    }
}
