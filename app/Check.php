<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Accounting\Entities\AccountingAccount;
class Check extends Model
{
    use HasFactory;

    // Fillable attributes
    protected $fillable = [
        'check_number','created_by','business_id','account_id', 'bank_id', 'cost_center', 'issue_date', 'due_date', 'check_value', 'currency', 'notes', 'check_type'

    ];

    // Date casting
    protected $casts = [
        'issue_date' => 'datetime',
        'due_date' => 'datetime',
    ];

    // Relationship with CheckTransaction model
    public function transactions()
    {
        return $this->hasMany(CheckTransaction::class);
    }
    /**
     * Get the user that owns the Check
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bankaccount()
    {
        return $this->belongsTo(AccountingAccount::class, 'bank_id');
    }
    public function account()
    {
        return $this->belongsTo(AccountingAccount::class, 'account_id');
    }
    public function getAccountNameAttribute(){
        return $this->account->name ?? '' ;
    }
    public function getBankAttribute()  {
        return $this->bankaccount->name ?? '' ;
        
    }
}
