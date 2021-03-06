<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Psubscription;
use App\Ldeduction;
use App\User;

use Carbon\Carbon;

class Lsubscription extends Model
{
    //
    //relationship with product
        public function product(){
        return $this->belongsTo(Product::class,'product_id');
        }
      //relationship with user
        public function user(){
        return $this->belongsTo(User::class);
        }
    
        //loan relationship
        //Each loan subscription belongs to a loan
        public function loan(){
            return $this->belongsTo(Loan::class);
        }
        //A loan subscription may have many deductions
        public function loandeductions(){
          return $this->hasMany(Ldeduction::class);
      }
    
    protected $dates = ['created_at', 'updated_at','loan_start_date','loan_end_date'];


    // public function testFunction(){
    //     $filterResult = Lsubscription::whereBetween('created_at',[$this->start_date,$this->end_date])
    //     ->with(['loan','user']);
    //     return $filterResult->where('repayment_mode',$this->pay_type)->unique('user_id');
    // }

    //All active loan subscriptions
    public static function loanSubscriptions(){
         return  static::where('loan_status', 'Active')
         ->with(['user','product'])
         ->get();
    }

     //Filter loan subscriptions
     public static function filterResult($pay_type,$start_date,$end_date){
        $result = static::whereBetween('created_at',[$start_date,$end_date])
        ->with(['loan','user'])
        ->get();

        return $result->where('repayment_mode',$pay_type);
    }

 
   
    //distint user loan subscriptions
    public static function distinctUserLoanSub(){
        $records = static::where('loan_status', 'Active')
        ->with(['user','product'])
        ->get();
       return $records->unique('user_id');
   }

    //Sum cumulative amount of IPPIS
    public  function totalIppisDeductions($_id)
    {
        $loanSub = Lsubscription::where('user_id',$_id)
                                ->where('loan_status','Active')
                                ->sum('monthly_deduction');
                                return $loanSub;
    }

    //user loan end date
    public function loanEndDate($_id){
        $loanSubObj = Lsubscription::where('user_id',$_id)
        ->where(function($query){
            $query->where('loan_status','Active');
        })
        ->orderBy('loan_end_date','asc')->take(1)->first();
      
        return $loanSubObj->loan_end_date;
    }


    //subscription end date

    public function SubEndDate($date,$tenor){
        $date_val = new Carbon($date);
        return $date_val->addMonths($tenor)->toDateString();
    }
    
    //user Product Subscription
    // public function productSubEndDate($_id){
    //     $prodSub = Psubscription::where('user_id',$_id)
    //     ->where(function($query){
    //         $query->where('status','Active');
    //     })
    //     ->orderBy('end_date','desc')->take(1)->first();
    //     if($prodSub == ""){
    //     //Do nothing
    //     }else{
    //         return $prodSub->end_date;
    //     }
    // }
    //compare dates
    public function compareDates($prodDate,$loanDate){
        if($prodDate){
            //check which one one is bigger
            if($prodDate < $loanDate){
                return $loanDate;
            }else{
                return $prodDate;
            }
        }else{
            return $loanDate;
        }
    }

    //Individual loan deductions
    public  function userLoanDeductions($_id)
    {
        $loanSub = Lsubscription::where('user_id',$_id)
        ->where(function($query){
            $query->where('loan_status','Active');
        })
        ->sum('monthly_repayment');
    }
        
    //User Active loans
    public static function activeLoans($id){
        return static::where('user_id',$id)
        ->where(function ($query){
            $query->where('loan_status','Active');
        })->with(['product' => function ($query) {
        $query->orderBy('name', 'desc');
        }])->get();
    }

      //User pending loans
public static function pendingLoans($id){
  return static::where('user_id',$id)
        ->where(function ($query){
            $query->where('loan_status','!=','Active');
        })->with(['product' => function ($query) {
        $query->orderBy('name', 'desc');
        }])->get();
}

//Find Total deduction for a given loan subscription
//Pass in loan subscription id
public  function totalLoanDeductions($loan_id)
{
    return Ldeduction::where('lsubscription_id',$loan_id)
                       ->sum('amount_deducted');
}

   //Product guarantor count
   public function guarantor($id){
    //find user
     $user = User::find($id);
     return $user->first_name .' '.$user->last_name;
}

      
}
