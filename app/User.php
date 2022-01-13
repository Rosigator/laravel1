<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'profession_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    protected $casts = [
        'is_admin' => 'boolean'
    ];

    public static function findByEmail(string $email)
    {
        return static::where(compact('email'))->first();
    }

    public function isAdmin()
    {
        return $this->is_admin;
    }

    public function profession()
    {
        return $this->belongsTo(Profession::class);
    }
}
