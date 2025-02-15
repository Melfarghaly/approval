<?php

namespace App\Actions;

use App\Business;
use App\Transaction;
use App\Utils\Util;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CreatePurchaseRequisition
{
    public function __construct()
    {
        $this->commonUtil = new Util();
    }

    public function execute(array $data): Transaction
    {
        return DB::transaction(function () use ($data) {

            $type = 'purchase_requisition';

            $purchasesLines = $this->getPurchaseLines($data['purchases']);

            $data['ref_no'] = $this->updateAndGetRefCount($type, $data['ref_no'] ?? null);

            $purchase_requisition = Transaction::create($data + [
                    'business_id' => request()->session()->get('user.business_id'),
                    'type' => $type,
                    'status' => 'ordered',
                    'created_by' => auth()->id(),
                    'transaction_date' => Carbon::now()->toDateTimeString(),
                ]);

            $purchase_requisition->purchase_lines()->createMany($purchasesLines);

            $business_id = request()->session()->get('user.business_id');
            $business = Business::where('id', $business_id)->first();

            (new CreateTransactionApproval())->execute($purchase_requisition, $business, 'purchase_request');

            return $purchase_requisition;
        });
    }

    private function getPurchaseLines(array $purchases): Collection
    {
        return collect($purchases)->map(function ($purchase) {
            $purchase['quantity'] = $this->commonUtil->num_uf($purchase['quantity']);
            $purchase['secondary_unit_quantity'] = 0;
            $purchase['purchase_price_inc_tax'] = 0;
            $purchase['item_tax'] = 0;

            return $purchase;
        });
    }

    private function updateAndGetRefCount(string $type, $ref_no)
    {
        $refNumber = $ref_no;

        $updateRefCount = $this->commonUtil->setAndGetReferenceCount($type);

        if (!$refNumber) {
            $ref_no = $this->commonUtil->generateReferenceNumber($type, $updateRefCount);
        }

        return $ref_no;
    }

}