<?php

namespace App\Imports;

use App\TargetSaving;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
//use Maatwebsite\Excel\Concerns\WithBatchInserts;
//use Maatwebsite\Excel\Concerns\WithChunkReading;

class TargetSavingImport implements ToModel, WithHeadingRow //, WithBatchInserts, WithChunkReading
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new TargetSaving([
            //
            'user_id' => $row['user_id'],
            'amount' => $row['target_saving'],
            'entry_date' => $row['date'],
            'created_by' => $row['staff_id'],
        ]);
    }
}
