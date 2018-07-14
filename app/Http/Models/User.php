<?php

namespace App\Http\Models;
use App\Notifications\ResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable {

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    public function statuses()
    {   
        return $this->hasMany(Status::class);
    }
    public static function boot() {
        parent::boot();
        static::creating(function($user) {
            $user->activation_token = str_random(30);
        });
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * 获得gravatar 头像地址
     * @param type $size
     * @return type
     */
    public function gravatar($size = 100) {
        $hash = md5(strtolower(trim($this->attributes['email'])));
        return "http://www.gravatar.com/avatar/$hash?s=$size";
    }

    public function sendPasswordResetNotification($token) {
        $this->notify(new ResetPassword($token));
    }

    public function feed(){
        return $this->statuses()
                ->orderBy('created_at','desc');
    }
}
