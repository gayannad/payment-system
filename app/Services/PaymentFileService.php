<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PaymentFileService
{
    /**
     * Uploads a file to an S3 storage
     */
    public function uploadToS3(UploadedFile $file, string $directory = 'payment-files'): string
    {
        $fileName = $directory.'/'.date('Y/m/d').'/'.Str::uuid().'.'.$file->getClientOriginalExtension();
        Storage::disk('s3')->put($fileName, file_get_contents($file->getRealPath()));

        return $fileName;
    }
}
