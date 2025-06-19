<?php

namespace App\Traits;

use App\{
    Models\Files\Files
};

use Illuminate\{
    Http\UploadedFile,
    Support\Facades\Storage
};
use Illuminate\Support\Facades\Log;

trait HasUploadFile
{
    public function uploadFile(UploadedFile $file, $relationName = 'foto', $directory = 'uploads')
    {
        if ($this->$relationName) {
            Storage::disk('public')->delete($this->$relationName->path);
            $this->$relationName->delete();
        }

        $storedPath = $file->store($directory, 'public');

        $fileModel = new Files([
            'path'           => $storedPath,
            'original_name'  => $file->getClientOriginalName(),
            'size'           => $file->getSize(),
            'mime_type'      => $file->getClientMimeType(),
        ]);

        $this->$relationName()->save($fileModel);

        return $fileModel;
    }

public function uploadMultipleFiles(array $files, string $relationName = 'photo', string $disk = 'public', string $path = 'uploads')
{
    Log::info('Masuk ke uploadMultipleFiles', ['files_count' => count($files)]);

    foreach ($files as $file) {
        if ($file instanceof \Illuminate\Http\UploadedFile && $file->isValid()) {
            $storedPath = $file->store($path, $disk);

            Log::info('Menyimpan file', [
                'original' => $file->getClientOriginalName(),
                'stored_path' => $storedPath
            ]);

            $this->$relationName()->create([
                'path'          => $storedPath,
                'original_name' => $file->getClientOriginalName(),
                'size'          => $file->getSize(),
                'mime_type'     => $file->getMimeType(),
            ]);
        } else {
            Log::warning('File tidak valid atau bukan UploadedFile', ['type' => gettype($file)]);
        }
    }
}

    public function uploadBase64Files(array $files, string $relationName = 'photo', string $path = 'uploads', string $disk = 'public')
    {
        foreach ($files as $file) {
            if (!isset($file['base64']) || !isset($file['type'])) {
                Log::warning('Invalid file data', ['file' => $file]);
                continue;
            }

            $base64Str = preg_replace('/^data:\w+\/\w+;base64,/', '', $file['base64']);
            $base64Str = str_replace(' ', '+', $base64Str);
            $decoded = base64_decode($base64Str);

            if ($decoded === false) {
                Log::error('Gagal decode base64', ['base64' => substr($base64Str, 0, 30)]);
                continue;
            }

            $extension = explode('/', $file['type'])[1] ?? 'jpg';
            $filename = uniqid('file_') . '.' . $extension;
            $storedPath = $path . '/' . $filename;

            Storage::disk($disk)->put($storedPath, $decoded);

            $this->$relationName()->create([
                'path'          => $storedPath,
                'original_name' => $file['name'] ?? $filename,
                'size'          => $file['size'] ?? strlen($decoded),
                'mime_type'     => $file['type'],
            ]);

            Log::info('File base64 disimpan', [
                'stored_path' => $storedPath,
                'fileable_id' => $this->getKey(),
            ]);
        }
    }
}
