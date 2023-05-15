<?php

namespace App\Services;

use App\Support\Mp3\MP3File;
use Webpatser\Uuid\Uuid;
use Intervention\Image\Facades\Image;

class FileService
{
    public function save_file(string $key, string $path): string|null
    {
        if(request()->hasFile($key)){
            $uuid = Uuid::generate(4)->string;
            $file = $uuid.'-'.request()[$key]->hashName();


            request()[$key]->storeAs($path,$file);
            return $file;
        }
        return null;
    }

    public function save_image(string $key, string $path): string|null
    {
        if(request()->hasFile($key)){
            $uuid = Uuid::generate(4)->string;
            $file = $uuid.'-'.request()[$key]->hashName();

            $img = Image::make(request()->file($key)->getRealPath());
            $img->resize(300, 200, function ($constraint) {
                $constraint->aspectRatio();
            })->save(storage_path('app/'.$path).'/'.'compressed-'.$file);

            request()[$key]->storeAs($path,$file);
            return $file;
        }
        return null;
    }

    public function mp3_file_duration(string $name): string|null
    {
        try {
            //code...
            $mp3file = new MP3File(storage_path('app/public/upload/audios/'.$name));//http://www.npr.org/rss/podcast.php?id=510282
            $duration2 = $mp3file->getDuration();//(slower) for VBR (or CBR)
            return MP3File::formatTime($duration2);
        } catch (\Throwable $th) {
            //throw $th;
            return null;
        }
    }

    public function document_page_number(string $name): string|null
    {
        $pdftext = file_get_contents(storage_path('app/public/upload/documents/'.$name));

        return preg_match_all("/\/Page\W/", $pdftext,$dummy);
    }

    public function remove_file(string $file_name, string $path): void
    {
        if($file_name!=null && file_exists(storage_path($path).$file_name)){
            unlink(storage_path($path.$file_name));
        }
    }
}
