<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Mark
 *
 * @property-read mixed $average_score
 * @property-read mixed $data
 * @property-read \App\Models\User $owner
 * @property-read \App\Models\Submission $submission
 * @mixin \Eloquent
 * @property int $id
 * @property int $submission_id
 * @property int $owner_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mark whereAverageScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mark whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mark whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mark whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mark whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mark whereSubmissionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mark whereUpdatedAt($value)
 */
class Mark extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'owner_id', 'average_score', 'data',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function submission()
    {
        return $this->belongsTo(Submission::class, 'submission_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function getDataAttribute($value)
    {
        return is_null($value) ? null : json_decode($value);
    }

    public function getAverageScoreAttribute($value)
    {
        return is_null($value) ? null : round($value, 2);
    }
}
