<?php

namespace App\Services;

use App\Contracts\EmailServiceInterface;
use App\Models\User;

class UserRegisterService
{
	protected EmailServiceInterface $emailService;

	public function __construct(EmailServiceInterface $emailService)
	{
		$this->emailService = $emailService;
	}

	public function register(array $data): User
	{
		$user = User::create($data);
		$this->emailService->sendWelcomeEmail($user);
		return $user;
	}
}
