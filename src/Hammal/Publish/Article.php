<?php
namespace App\Http\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
	use SoftDeletes;
    protected $table = 'article';

    protected $fillable = [
        'id','name'
    ];

    protected $hidden = [
    ];
   /*
   public function category()
   {
   		return $this->belongsTo('App\Http\Models\Category');
   }
	public function getCreatedAtAttribute($value)
	{
	  return date('d-m-Y',strtotime($value));
	}
	public function getUpdatedAtAttribute($value)
	{
	  return date('d-m-Y',strtotime($value));
	}
	*/

}
