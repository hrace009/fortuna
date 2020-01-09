<?php

namespace App\Services\FileManager\Traits;

use App\Models\File;
use App\Services\FileManager\FileManager;
use Illuminate\Http\UploadedFile;

trait UploadFile
{
    /**
     * Path where file should be stored.
     *
     * @var string
     */
    public $imagePath;

    /**
     * Get image path.
     *
     * @return void
     */
    public function getImagePath()
    {
        return $this->imagePath;
    }

    /**
     * Define where image should be stored.
     *
     * @param string $path
     * @return void
     */
    public function setImagePath($path)
    {
        return $this->imagePath = $path;
    }

    /**
     * Get files from the given model.
     *
     * @return void
     */
    public function file()
    {
        return $this->morphMany(File::class, 'attachable');
    }

    /**
     * Get avatar from the given model.
     *
     * @return void
     */
    public function avatar()
    {
        return $this->morphOne(File::class, 'attachable');
    }

    /**
     * Upload and store the given file.
     *
     * @return void
     */
    public function upload(UploadedFile $file, $visiblity = null)
    {
        return (new FileManager($this))->file($file)->upload($visiblity);
    }

    /**
     * Retrieves file's url.
     *
     * @return void
     */
    public function showFile()
    {
        return (new FileManager($this))->showFile();
    }

    public function showTemporaryFile()
    {
        return (new FileManager($this))->showTemporaryFile();
    }
}
