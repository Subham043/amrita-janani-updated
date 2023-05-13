<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Uuid;
use File;
use Auth;

class BannerQuoteModel extends Model
{
    use HasFactory, SoftDeletes;
    protected $table="banner_quotes";

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'quote',
        'user_id'
    ];

    public function User()
    {
        return $this->belongsTo('App\Models\User')->withDefault();
    }


}
