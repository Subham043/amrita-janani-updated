<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FAQModel extends Model
{
    use HasFactory, SoftDeletes;
    protected $table="faqs";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'question',
        'answer',
        'user_id',
    ];


    public function User()
    {
        return $this->belongsTo('App\Models\User')->withDefault();
    }

}
