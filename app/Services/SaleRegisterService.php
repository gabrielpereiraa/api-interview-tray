<?php

namespace App\Services;

use App\Contracts\CommissionServiceInterface;
use App\Contracts\EmailServiceInterface;
use App\Models\Sale;
use App\Models\Seller;
use App\Models\User;

class SaleRegisterService
{
    protected EmailServiceInterface $emailService;
    protected CommissionServiceInterface $commissionService;

    public function __construct(EmailServiceInterface $emailService, CommissionServiceInterface $commissionService)
    {
        $this->emailService = $emailService;
        $this->commissionService = $commissionService;
    }

    public function register(User $user, Seller $seller, array $data): Sale
    {
        $data['commission'] = $this->commissionService->calculate($data['amount']);
        $sale = Sale::create($data);
        $this->emailService->sendNewSaleEmail($user, $seller, $sale);
        return $sale;
    }

    public function update(User $user, Seller $seller, Sale $sale, array $data): Sale
    {
        $data['commission'] = $this->commissionService->calculate($data['amount']);
        $oldSale = $sale;
        $sale->update($data);
        $this->emailService->sendUpdatedSaleEmail($user, $seller, $oldSale, $sale);
        return $sale;
    }

    public function delete(User $user, $sale): bool
    {
        $sale->deleted_by = $user->id;
        $sale->save();
        return $sale->delete();
    }
}
