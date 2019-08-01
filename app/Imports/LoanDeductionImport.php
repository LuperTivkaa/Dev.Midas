<?php

namespace App\Imports;

use App\Ldeduction;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LoanDeductionImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Ldeduction([
                //
                'user_id' => $row['userid'],
                'loan_id' => $row['loanid'],
                'entry_month' => $row['date'],
                'amount_deducted' => $row['amount'],
                'lsubscription_id' => $row['subscriptionid'],
                'uploaded_by' => auth()->id(),
        ]);
    }
}
