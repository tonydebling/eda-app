<?php

namespace Target\Validation;

use Violin\Violin;
use Target\User\User;
use Target\Helpers\Hash;

class Validator extends Violin
{
	protected $user;
	protected $hash;
	protected $auth;
	
	public function __construct(User $user, Hash $hash, $auth = null)
	{
		$this->user = $user;
		$this->hash = $hash;
		$this->auth = $auth;
		
		$this->addFieldMessages([
			'email' => [
				'uniqueEmail' => 'That email is already in use.',
				'existingEmail' => 'Email not registered.'
			],
			'username' => [
				'uniqueUsername' => 'That username is already in use.'
			]
		 ]);
		 
		 $this->addRuleMessages([
			'matchesCurrentPassword' 	=> 'That does not match current password.'
		]);
	}
	
	public function validate_uniqueEmail($value, $input, $args)
	{
		$user = $this->user->where('email',$value);
		return !(bool) $user->count();
	}
	
	public function validate_existingEmail($value, $input, $args)
	{
		$user = $this->user->where('email',$value)->first();
		return (bool) $user;
	}
	
	public function validate_uniqueUsername($value, $input, $args)
	{
		$user = $this->user->where('username',$value);
		return !(bool) $user->count();
	}
	
	public function validate_matchesCurrentPassword($value, $input, $args)
	{
		if ($this->auth && $this->hash->passwordCheck($value, $this->auth->password)){
			return true;
		}
		return false;
	}	
	
}
