<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, CanResetPassword, EntrustUserTrait;
    const MALE = 0;
    const FEMALE = 1;

    //  Tester ID ranges
    const ZERO_TO_TWO = 0;
    const THREE_TO_FIVE = 1;
    const SIX_TO_EIGHT = 2;
    const NINE = 3;
    //	Default password
	  const DEFAULT_PASSWORD = '123456';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];
    public function getIsAdminAttribute()
    {
        return true;
    }
    /**
     * Get the admin user currently the first user
     *
     * @return User model
     */
    public static function getAdminUser()
    {
        return User::find(1);
    }

    /**
  	 * Return readable tester-id-range
  	 */
  	public static function range($range)
  	{
  		  if($range == User::ZERO_TO_TWO)
            return '0 - 2';
        else if($range == User::THREE_TO_FIVE)
            return '3 - 5';
        else if($range == User::SIX_TO_EIGHT)
            return '6 - 8';
        else if($range == User::NINE)
            return '9';
  	}
    /**
	  * Relationship with user-tier
	  *
	  * @return RoleUserTier object
	  */
  	public function tier()
  	{
  		return $this->hasOne('App\Models\Tier');
  	}
}
