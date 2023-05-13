<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\Models\DocumentAccess;
use App\Models\DocumentFavourite;
use App\Models\LanguageModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Webpatser\Uuid\Uuid;

class DocumentModel extends Model
{
    use HasFactory, SoftDeletes;
    protected $table="documents";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'uuid',
        'description',
        'description_unformatted',
        'tags',
        'year',
        'version',
        'page_number',
        'language_id',
        'deity',
        'views',
        'favourites',
        'document',
        'topics',
        'status',
        'restricted',
        'user_id',
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = (string) Uuid::generate(4);
        });
    }

    public function User()
    {
        return $this->belongsTo('App\Models\User')->withDefault();
    }

    public function getAdminName(){
        if(!empty($this->User) && $this->User->count()>0){
            return $this->User->name;
        }
        return "";
    }

    public function Languages()
    {
        return $this->belongsToMany(LanguageModel::class, 'document_languages', 'document_id', 'language_id');
    }

    public function GetLanguagesId(){
        return $this->Languages()->pluck('languages.id')->toArray();
    }

    public function GetLanguagesName(){
        return $this->Languages()->pluck('languages.name');
    }

    public function DocumentFavourite()
    {
        return $this->hasMany('App\Models\DocumentFavourite', 'document_id');
    }

    public function DocumentAccess()
    {
        return $this->hasMany('App\Models\DocumentAccess', 'document_id');
    }

    public function DocumentReport()
    {
        return $this->hasMany('App\Models\DocumentReport', 'document_id');
    }

    public function file_format(){
        return File::extension($this->document);
    }

    public function time_elapsed(){

        $dt = Carbon::parse($this->created_at);
        return $dt->diffForHumans();

    }

    public function contentVisible(){
        try {
            $documenetAccess = DocumentAccess::where('documenet_id', $this->id)->where('user_id', Auth::user()->id)->first();
        } catch (\Throwable $th) {
            //throw $th;
            $documenetAccess = null;
        }

        if($this->restricted==0 || Auth::user()->userType!=2){
            return true;
        }else{
            if(empty($documenetAccess) || $documenetAccess->status==0){
                return false;
            }else{
                return true;
            }
        }
    }

    public function markedFavorite(){
        try {
            $documentFav = DocumentFavourite::where('document_id', $this->id)->where('user_id', Auth::user()->id)->first();
        } catch (\Throwable $th) {
            //throw $th;
            $documentFav = null;
        }
        if(!empty($documentFav)){
            if($documentFav->status == 1){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }

    }

    public function getTagsArray() {
        if($this->tags){
            $arr = explode(",",$this->tags);
            return $arr;
        }
        return array();
    }

}
