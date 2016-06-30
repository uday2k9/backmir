<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
	public $timestamps = false;
    // protected $fillable=[
    //     'name',
    //     'price',
    //     'maximum_weight',
    //     'minimum_weight'
    //    ];

    protected $guarded = array();  // Important

    protected $table = 'products';

    public function AllProductFormfactors()
    {
        return $this->hasMany('App\Model\ProductFormfactor');
    }

    public function AllTags()
    {
        return $this->hasMany('App\Model\Tag');
    }
    public function AllFillingTags()
    {
        return $this->hasMany('App\Model\Tag')->where('type','=','1');
    }
    public function AllIngredientTags()
    {
        return $this->hasMany('App\Model\Tag')->where('type','=','2');
    }


    public function GetBrandDetails()
    {
        return $this->belongsTo('App\Model\Brandmember','brandmember_id');
    }



}
