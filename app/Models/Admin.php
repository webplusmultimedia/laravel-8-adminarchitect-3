<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Terranet\Administrator\Notifications\ResetPassword;

class Admin extends Authenticatable
{
    use Notifiable;



    const ROLE_SUPER_ADMIN = 1;
    const ROLE_ADMIN = 2;
    const ROLE_EDITOR = 3;

    const ROLES =[
        self::ROLE_SUPER_ADMIN => 'Super admin',
        self::ROLE_ADMIN => 'Administrateur',
        self::ROLE_EDITOR => "Editeur"
    ];

    const ADMIN_ROLES = [
        self::ROLE_SUPER_ADMIN,
        self::ROLE_ADMIN ,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','role',
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
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isSuperAdmin(): bool
    {
        return   $this->hasRole(self::ROLE_SUPER_ADMIN) OR $this->hasRole(self::ROLE_ADMIN) OR $this->hasRole(self::ROLE_EDITOR);
    }


    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }


    /**
     * @param $role
     * @return bool
     */
    public function hasRole(int $role): bool
    {
        return auth('admin')->user()->role === $role;
    }

    public function isOwnerOf($entity)
    {
        return auth('admin')->id() === $entity->user_id;

    }

    public function getIsAdminAttribute()
    {
        return $this->role == self::ROLE_ADMIN;
    }

    public function getIsEditorAttribute()
    {
        return $this->role == self::ROLE_EDITOR;
    }

    public function getIsSuperAdminAttribute()
    {
        return $this->role == self::ROLE_SUPER_ADMIN;
    }

    public function getIsAdminRolesAttribute()
    {
        return in_array($this->role , self::ADMIN_ROLES);
    }

    public static function lesRoles(): array
    {
        $roles = self::ROLES;
        if(auth()->user()->role==self::ROLE_SUPER_ADMIN)
            return $roles;
        else{
            unset($roles[self::ROLE_SUPER_ADMIN]);
            return $roles;
        }
    }

}
