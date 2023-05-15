<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Uuid;
use Carbon\Carbon;
use File;
use Auth;

class PageModel extends Model
{
    use HasFactory, SoftDeletes;
    protected $table="pages";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'page_name',
        'url',
        'user_id',
    ];


    public function User()
    {
        return $this->belongsTo('App\Models\User')->withDefault();
    }

    public function PageContentModel()
    {
        return $this->hasMany('App\Models\PageContentModel', 'page_id');
    }

}
