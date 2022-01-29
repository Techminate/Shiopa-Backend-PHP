<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role_id',
        'is_admin',
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role(){
        return $this->hasOne(Role::class,'id','role_id');
    }

    public function profile(){
        return $this->hasOne(Profile::class);
    }

    public function order(){
        return $this->hasMany(Order::class);
    }

    public function sendPasswordResetNotification($token)
    {
        $url = 'https://tnbpos.com/reset-password?token=' . $token;

        $this->notify(new ResetPasswordNotification($url));
    }

    public function format(){
        return[
            'user_id' => $this->id,
            'name' => $this->name,
            'role' => $this->role->name,
            'email' => $this->email,
            'created_at'=>$this->created_at->diffForHumans(),
            'updated_at'=>$this->updated_at->diffForHumans()
        ];
    }
}
