<?php

namespace App\Services\FileManager;

use App\Models\User;
use Illuminate\Support\Str;
use Laravolt\Avatar\Facade as Avatar;

class DefaultAvatar
{
    private const Filename = 'avatar';
    private const Extension = '.jpg';
    private $user;
    private $avatar;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function create()
    {
        $this->generate();
        \DB::transaction(function () {
            $this->avatar = $this->user->avatar()
                ->firstOrcreate(['user_id' => $this->user->id]);
            $this->avatar->file()->create($this->attributes());
        });

        return $this->avatar;
    }

    private function generate()
    {
        Avatar::create($this->user->name)
            ->getImageObject()
            ->save($this->savePath());
    }

    private function attributes()
    {
        return [
            'original_name' => $this->filename(),
            'name' => $this->hashName(),
            'size' => \File::size($this->savePath()),
            'mime' => \File::mimeType($this->savePath()),
        ];
    }

    private function filename()
    {
        return self::Filename.$this->user->id.self::Extension;
    }

    private function hashName()
    {
        return $this->hashName
            ?? $this->hashName = Str::random(40).self::Extension;
    }

    private function savePath()
    {
        $folder = app()->environment() === 'testing'
            ? FileManager::TestingFolder
            : 'avatars';
        if (! \Storage::has($folder)) {
            \Storage::makeDirectory($folder);
        }

        return storage_path('app'.DIRECTORY_SEPARATOR.$folder.DIRECTORY_SEPARATOR.$this->hashName());
    }
}
