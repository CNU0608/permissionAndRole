<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Role;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function roles(){
        return $this->belongsToMany(Role::class);
    }

    // 判断用户是否具有某个角色
    public function hasRole($role){
        if( is_string($role) ){
            return $this->roles->contains('name', $role);
        }
        return !! $role->intersect($this->roles)->count();
    }

    // 判断用户是否具有某个权限
    public function hasPermission($permission){
        return $this->hasRole($permission->roles);
    }

    // 给用户分配角色
    public function assignRole($role){
        return $this->roles()->save( Role::whereName($role)->firstOrFail() );
    }
}
