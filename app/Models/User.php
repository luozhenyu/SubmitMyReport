<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
use App\Notifications\SiteMessage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\UploadedFile;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * App\Models\User
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Assignment[] $createdAssignments
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Group[] $createdGroups
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Mark[] $createdMarks
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Submission[] $createdSubmissions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\File[] $files
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Group[] $joinedGroups
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $siteMessages
 * @mixin \Eloquent
 * @property int $id
 * @property string $student_id
 * @property string|null $email
 * @property string $name
 * @property string $password
 * @property string|null $remember_token
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereUpdatedAt($value)
 */
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function createdGroups()
    {
        return $this->hasMany(Group::class, 'owner_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function createdAssignments()
    {
        return $this->hasMany(Assignment::class, 'owner_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function createdSubmissions()
    {
        return $this->hasMany(Submission::class, 'owner_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function createdMarks()
    {
        return $this->hasMany(Mark::class, 'owner_id');
    }

    /**
     * 物理存储
     * @param UploadedFile $uploadedFile
     * @param string $filename
     * @return File
     */
    public function storeFile(UploadedFile $uploadedFile, string $filename)
    {
        $sha512 = hash_file("sha512", $uploadedFile->path());

        if (!$storedFile = $this->files()->where([
            ['sha512', $sha512],
            ['filename', $filename],
        ])->first()) {

            $targetDir = File::hashToPath($sha512);
            if (!Storage::exists($targetDir)) {
                Storage::makeDirectory($targetDir);
            }
            $uploadedFile->storeAs(
                $targetDir, $sha512
            );

            $storedFile = $this->files()->create([
                'random' => Str::random(80),
                'sha512' => $sha512,
                'size' => filesize($uploadedFile),
                'filename' => $filename,
            ]);
        }
        return $storedFile;
    }

    public function files()
    {
        return $this->hasMany(File::class, 'owner_id');
    }

    public function managedGroups()
    {
        return $this->joinedGroups()
            ->wherePivot('is_admin', true);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function joinedGroups()
    {
        return $this->belongsToMany(Group::class, 'group_user', 'user_id', 'group_id')
            ->withPivot('is_admin')
            ->withTimestamps();
    }

    /**
     * 发送密码重置通知.
     *
     * @param  string $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * Get the entity's sent siteMessages.
     * @param User|null $to
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function sentSiteMessages(User $to = null)
    {
        $query = $this->siteMessages()
            ->where(DB::raw("cast(data->>'type' as int)"), SiteMessage::sent);

        return $to ? $query->where(DB::raw("cast(data->>'to' as int)"), $to->id) : $query;
    }

    /**
     * Get the entity's siteMessages.
     * @param User|null $theOther
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function siteMessages(User $theOther = null)
    {
        $query = $this->morphMany(DatabaseNotification::class, 'notifiable')
            ->where('type', SiteMessage::class);

        return $theOther ? $query->where(function (Builder $query) use ($theOther) {
            $query->where([
                [DB::raw("cast(data->>'type' as int)"), SiteMessage::sent],
                [DB::raw("cast(data->>'to' as int)"), $theOther->id],
            ])->orWhere([
                [DB::raw("cast(data->>'type' as int)"), SiteMessage::received],
                [DB::raw("cast(data->>'from' as int)"), $theOther->id],
            ]);
        }) : $query;
    }

    /**
     * Get the entity's unread siteMessages.
     * @param User $from
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function unreadReceivedSiteMessages(User $from = null)
    {
        return $this->receivedSiteMessages($from)
            ->whereNull('read_at');
    }

    /**
     * Get the entity's received siteMessages.
     * @param User $from
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function receivedSiteMessages(User $from = null)
    {
        $query = $this->siteMessages()
            ->where(DB::raw("cast(data->>'type' as int)"), SiteMessage::received);

        return $from ? $query->where(DB::raw("cast(data->>'from' as int)"), $from->id) : $query;

    }

}