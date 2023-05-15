<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PageContentModel extends Model
{
    use HasFactory, SoftDeletes;
    protected $table="page_contents";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'heading',
        'image',
        'image_position',
        'description',
        'description_unformatted',
        'page_id',
    ];


    public function PageModel()
    {
        return $this->belongsTo('App\Models\PageModel', 'page_id')->withDefault();
    }

}
