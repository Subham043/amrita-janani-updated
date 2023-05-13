<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Uuid;
use File;
use Auth;

class BannerModel extends Model
{
    use HasFactory, SoftDeletes;
    protected $table="banners";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'image',
        'user_id'
    ];

    public function User()
    {
        return $this->belongsTo('App\Models\User')->withDefault();
    }


}
