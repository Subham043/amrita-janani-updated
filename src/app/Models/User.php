<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'userType',
        'otp',
        'status',
        'password',
        'darkMode',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $attributes = [
        'userType' => 2,
        'status' => 0,
        'allowPasswordChange' => 0,
    ];

    protected function status(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => $value == "on" ? 1 : 2,
        );
    }

    public function getPassword(){
        return $this->password;
    }

    public function ImageModel()
    {
        return $this->hasMany('App\Models\ImageModel', 'user_id');
    }

    public function DocumentModel()
    {
        return $this->hasMany('App\Models\DocumentModel', 'user_id');
    }

    public function AudioModel()
    {
        return $this->hasMany('App\Models\AudioModel', 'user_id');
    }

    public function VideoModel()
    {
        return $this->hasMany('App\Models\VideoModel', 'user_id');
    }

    public function ImageFavourite()
    {
        return $this->hasMany('App\Models\ImageFavourite', 'image_id');
    }

    public function DocumentFavourite()
    {
        return $this->hasMany('App\Models\DocumentFavourite', 'document_id');
    }

    public function AudioFavourite()
    {
        return $this->hasMany('App\Models\AudioFavourite', 'audio_id');
    }

    public function VideoFavourite()
    {
        return $this->hasMany('App\Models\VideoFavourite', 'video_id');
    }
}
?>
