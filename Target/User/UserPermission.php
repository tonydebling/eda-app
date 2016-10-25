<?php

namespace Target\User;

use Illuminate\Database\Eloquent\Model as Eloquent;

class UserPermission extends Eloquent{

	protected $table = 'users_permissions';
		
	protected $fillable = [
		'is_admin'
	];
	
	public static $defaults = [
		'is_admin' => false,
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
}
