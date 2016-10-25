<?php

namespace Target\User;

use Illuminate\Database\Eloquent\Model as Eloquent;

class User extends Eloquent{

	protected $table = 'users';
		
	protected $fillable = [
		'email',
		'username',
		'password',
		'active',
		'active_hash',
		'recover_hash',
		'remember_identifier',
		'remember_token',
	];
	
	public function getFullName()
	{
		if (!$this->first_name || !$this->last_name){
			return null;
		}
		
		return "{$this->first_name} {$this->last_name}";
	}
	public function getFullNameOrUsername()
	{
		return $this->getFullName() ?: $this->username;
	}
	
	public function activateAccount()
	{
		$this->update([
			'active' => true,
			'active_hash' => null
		]);
	}
	public function hasPermission($permission)
	{
		return (bool)$this->permissions->{$permission};
	}
	
	public function isAdmin()
	{
		return $this->hasPermission('is_admin');
	}
			
	public function permissions()
	{
		return $this->hasOne('Target\User\UserPermission', 'user_id');
	}
	
}
