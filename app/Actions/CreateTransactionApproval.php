<?php

namespace App\Actions;

use App\Business;
use App\Transaction;

class CreateTransactionApproval
{
    public function execute(Transaction $transaction, Business $business, string $typeOfApproval): void
    {
        $usersForApproval = match ($typeOfApproval) {
            'sale_order' => $business->approval_counter['sale_order'] ?? [],
            'purchase_request' => $business->approval_counter['purchase_request'] ?? [],
            'purchase_order' => $business->approval_counter['purchase_order'] ?? [],
            'purchase_permission' => $business->approval_counter['purchase_permission'] ?? [],
            default => $business->approval_counter['qoutation'] ?? []
        };

        $finalUserForApprovals = array_map(function ($item) {
            return ['user_id' => $item];
        }, $usersForApproval);

        $transaction->transactionApprovals()->createMany($finalUserForApprovals);
    }
}