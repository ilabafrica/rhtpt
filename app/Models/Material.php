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
}
