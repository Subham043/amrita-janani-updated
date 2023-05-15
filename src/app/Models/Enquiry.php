<?php

namespace App\Models;

use App\Jobs\SendAdminEnquiryEmailJob;
use App\Jobs\SendUserThankYouEmailJob;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Enquiry extends Model
{
    use HasFactory, SoftDeletes;
    protected $table="enquiries";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
    ];

    public static function boot()
    {
        parent::boot();
        self::created(function ($model) {
            $details['name'] = $model->name;
            $details['email'] = $model->email;
            $details['phone'] = $model->phone;
            $details['subject'] = $model->subject;
            $details['message'] = $model->message;

            dispatch(new SendUserThankYouEmailJob($details));
            dispatch(new SendAdminEnquiryEmailJob($details));
        });
    }
}
