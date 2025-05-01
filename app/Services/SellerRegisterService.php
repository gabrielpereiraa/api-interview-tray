<?php

namespace App\Services;

use App\Contracts\EmailServiceInterface;
use App\Models\Seller;
use App\Models\User;

class SellerRegisterService
{
	protected EmailServiceInterface $emailService;

	public function __construct(EmailServiceInterface $emailService)
	{
		$this->emailService = $emailService;
	}

	public function register(User $user, array $data): Seller
	{
		$seller = Seller::create($data);
		$this->emailService->sendWelcomeSellerEmail($user, $seller);
		return $seller;
	}
}
