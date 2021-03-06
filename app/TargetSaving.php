<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Targetsr;

class Targetsaving extends Model
{
    //
    protected $fillable = [
      'user_id', 
      'amount', 
      'entry_date',
      'created_by',
  ];
      //Each saving belongs to a user
      public function user(){
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship for target saving review
     * Each Target saving belongs to a Target saving review
     */
    public function targetsavingreview(){
        return $this->belongsTo(Targetsr::class);
    }

protected $dates = ['created_at', 'updated_at','start_date','entry_date','end_date'];

/**
 * Find the sum of user target saving
 * pass in the user id
 */
public static function myTargetSavings($id){
    /**
     * first find the target saving review that is active where user id is id
     */
    $tr = Targetsr::where('user_id',$id)
    ->where(function ($query) {
    $query->where('status', 'Active');
    })->get();
    foreach($tr as $t){
        $targetsr_id = $t->id;
    }
    return TargetSaving::where('targetsr_id',$targetsr_id)->sum('amount');
}

}
