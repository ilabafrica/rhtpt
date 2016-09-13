<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Material extends Model
{
  	/**
  	 * Enabling soft deletes for sample-preparation.
  	 *
  	 */
  	use SoftDeletes;
  	protected $dates = ['deleted_at'];

  	/**
  	 * The database table used by the model.
  	 *
  	 * @var string
  	 */
  	protected $table = 'materials';
    /**
  	 * Type of material
  	 *
  	 */
  	const WHOLE_BLOOD = 0;
  	const PLASMA = 1;
    const SLIDE = 2;
    const SERUM = 3;
    /**
  	 * User relationship
  	 *
  	 */
     public function user()
     {
          return $this->belongsTo('App\Models\User', 'prepared_by');
     }
     /**
   	 * Return readable material-type
   	 *
   	 */
     public function material($material)
     {
        if($material == Material::WHOLE_BLOOD)
            return 'Whole Blood';
        else if($material == Material::PLASMA)
            return 'Plasma';
        else if($material == Material::SLIDE)
            return 'Slide';
        else if($material == Material::SERUM)
            return 'Serum';
     }
}
