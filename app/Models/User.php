<?php

namespace App\Models;

use App\Models\Syllabus;
use App\Models\Invention;
use App\Models\Reference;
use App\Models\ExtensionService;
use Laravel\Sanctum\HasApiTokens;
use App\Models\ExpertServiceAcademic;
use Laravel\Jetstream\HasProfilePhoto;
use App\Models\Authentication\UserRole;
use App\Models\ExpertServiceConference;
use App\Models\ExpertServiceConsultant;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use SoftDeletes;
    
    protected $guarded = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'password',
        'role_id',
        'date_of_birth',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'emp_code',
        'emp_id',
        'signature',
        'user_account_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'email',
        'password',
        'date_of_birth',
        'emp_code',
        'emp_id',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function userrole() {
        return $this->hasMany(\App\Models\Authentication\UserRole::class);
    }

    public function expertserviceconsultant() {
        return $this->hasMany(\App\Models\ExpertServiceConsultant::class);
    }

    public function expertserviceconference() {
        return $this->hasMany(\App\Models\ExpertServiceConference::class);
    }

    public function expertserviceacademic() {
        return $this->hasMany(\App\Models\ExpertServiceAcademic::class);
    }

    public function extensionservice() {
        return $this->hasMany(\App\Models\ExtensionService::class);
    }

    public function invention() {
        return $this->hasMany(\App\Models\Invention::class);
    }

    public function reference() {
        return $this->hasMany(\App\Models\Reference::class);
    }

    public function syllabus() {
        return $this->hasMany(\App\Models\Syllabus::class);
    }

    public function notifications()
    {
        return $this->morphMany(DatabaseNotification::class, 'notifiable')
            ->orderBy('created_at', 'desc')
            ->limit(10);
    }
}
