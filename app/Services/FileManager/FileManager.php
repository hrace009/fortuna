<?php

namespace App\Services\FileManager;

use DB;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileManager
{
    protected $model;
    protected $disk = 's3';
    protected $folder;
    protected $file;
    protected $image;
    protected $optimize;
    protected $resize;
    protected $transformer;

    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * Set the file to upload.
     */
    public function file(UploadedFile $file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Upload file.
     */
    public function upload($visibility = null)
    {
        logger("Visibility $visibility");

        DB::transaction(function () use ($visibility) {
            $this->model->file()->create([
                'original_name' => $this->file->getClientOriginalName(),
                'name' => $this->file->hashName(),
                'size' => $this->file->getClientSize(),
                'mime' => $this->file->getClientMimeType(),
                'path' => $this->model->getImagePath(),
            ]);

            $this->file->store(
                $this->model->getImagePath(),
                [
                    'disk' => $this->disk,
                    'visibility' => $visibility,
                ]
            );
        });
    }

    public function showFile()
    {
        return Storage::disk($this->disk)->url($this->uploadedFile());
    }

    public function showTemporaryFile()
    {
        return Storage::disk($this->disk)->temporaryUrl($this->uploadedFile(), now()->addMinutes(5));
    }

    /**
     * Get uploaded file.
     */
    public function uploadedFile()
    {
        return $this->model->path.'/'.$this->model->name;
    }

    /**
     * The disk where the file will be stored.
     */
    public function disk(string $disk)
    {
        $this->disk = $disk;

        return $disk;
    }
}
