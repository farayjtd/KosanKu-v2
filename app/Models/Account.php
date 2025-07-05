<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
use Illuminate\Auth\Passwords\CanResetPassword as PasswordsCanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property \App\Models\Tenant|null $tenant
 * @property \App\Models\Landboard|null $landboard
 *
 * @method bool update(array $attributes = [], array $options = [])
 * @method $this fill(array $attributes)
 * @method bool save(array $options = [])
 */

class Account extends Authenticatable implements CanResetPassword
{
    use PasswordsCanResetPassword;
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'email',
        'password',
        'role',
        'is_first_login',
        'avatar',
        'bank_name',
        'bank_account',
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $casts = [
        'password' => 'hashed',
        'is_first_login' => 'boolean'
    ];

    public function tenant()
    {
        return $this->hasOne(Tenant::class);
    }

    public function landboard()
    {
        return $this->hasOne(Landboard::class);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
