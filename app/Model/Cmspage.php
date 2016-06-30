<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Cmspage extends Model
{
    protected $fillable=[
        'title',
        'slug',
        'meta_name',
        'meta_description',
        'meta_keyword',
        'description'
       ];
}
