<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class VueItem extends Model
{
    protected $table = 'vueitems';
    public $fillable = ['title','description'];

}