<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Saving extends Model
{
    //

    protected $fillable = [
        'user_id', 
        'amount_saved', 
        'entry_date',
        'created_by',
    ];

    protected $dates = ['entry_date','created_at', 'updated_at'];

    //Each saving belongs to a user
    public function user(){
        return $this->belongsTo(User::class);
    }

    /**
     * Total debit by the user
     * @param int $id
     */
    public function totalDebit($id){
        return Saving::where('user_id',$id)->sum('amount_withdrawn');
    }
    
    /**
     * Total debit by the user
     * @param int $id
     */
    public function totalCredit($id){
        return Saving::where('user_id',$id)->sum('amount_saved');
    }

    /**
     * Get user total savings
     * pass in user id
     * @param int $id
     */
    public static function mySavings($id){
        $savingObj = new Saving;
        //$totalSaving = Saving::where('user_id',$id)->sum('amount_saved');
        return $totalSaving = $savingObj->totalCredit($id)-$savingObj->totalDebit($id);
      }

    /**
     * Net balance
     * total balance without range  
     * from the begining of transactions
     * @param int $id
     */
    //TODO
    //CHECCK METHOD
    public function netBalance($id){
        return $this->mySavings($id)-$this->totalDebit($id);
    }

    /**
     * Opening balance method
     * @param date $from
     * @param int $id
     */
    public function openingBalance($from,$id){
        $startDate = new Carbon($from);
        // find previous month from startDate
        //  $prevMonth = $startDate->subMonth();
        // find end of previous month
        // $prevMonthEnd = $prevMonth->endOfMonth();
        //find all saving records of a user by id
        $savingCollection = Saving::where('user_id',$id);
        $savingsAt = $savingCollection->where('entry_date','<',$startDate)->sum('amount_saved');
        $debitAt = $savingCollection->where('entry_date','<',$startDate)->sum('amount_withdrawn');

        return $openingBalance = $savingsAt - $debitAt;
    }

    /**
     * Balance as at a given date
     * @param date $from
     * @param double $credit
     * @param double $debit
     * @param int $saving_id
     * @param int $id
     */
    public function balanceAsAt($from,$credit,$debit,$saving_id,$id){
        //$id = auth()->id();
        $startDate = new Carbon($from);
        $savingCollection = Saving::where('user_id',$id);
        //previous sum of savings before the current record
        $savingsAt = $savingCollection->where('id','<',$saving_id)
                                        ->sum('amount_saved');
        //dd($savingsAt);
        //Previous sum of debits
        $debitAt = $savingCollection->where('id','<',$saving_id)
                                    ->sum('amount_withdrawn');

        $balanceAt = $savingsAt - $debitAt;
        $currentBalance = $credit-$debit; 
        return $balanceAt + $currentBalance;
    }

    /**
     * Opening date
     * takes a date and return a formatted date
     * @param date $date
     */
    public function openingDate($date){
        $startDate = new Carbon($date);
        //return $startDate->startOfMonth()->toFormattedDateString();
        return $startDate->toFormattedDateString();
    }


    /**
     * Find savings
     * based on search range 
     * @param Date $from
     * @param Date $to
     * @param int $id
     */
    public function findSavingRecords($from,$to,$id){
        return  $collection = Saving::where('user_id',$id)
                                ->where('entry_date','>=',$from)
                                ->where('entry_date','<=',$to)
                                ->get()     
                                ->sortBy('id');
                                
    //return  $collection = DB::select('select * from savings where entry_date >=? and entry_date <=? and user_id=?',[$from,$to,$id])->orderBy('entry_date','asc');
        }

    /**
     * get user saving history
     *pass in user user id
     * @param int $id
     */
    public static function savingHistory($id){
        return static::where('user_id',$id)
        ->latest()->paginate(12);
    }

    /**
     *  
     */
       
}
