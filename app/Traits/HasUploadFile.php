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

    public function uploadMultipleBase64Files(array $files, string $relationName = 'photo', string $path = 'uploads', string $disk = 'public')
    {
        if ($this->$relationName()->exists()) {
            foreach ($this->$relationName as $oldFile) {
                try {
                    Storage::disk($disk)->delete($oldFile->path);
                    $oldFile->delete();
                    Log::info('Foto lama dihapus', ['path' => $oldFile->path]);
                } catch (\Throwable $e) {
                    Log::error('Gagal hapus file lama', ['error' => $e->getMessage()]);
                }
            }
        }

        foreach ($files as $file) {
            if (!isset($file['base64'], $file['type'])) {
                Log::warning('Data file tidak valid', ['file' => $file]);
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

            Log::info('File base64 disimpan', ['path' => $storedPath]);
        }
    }


    public function uploadBase64File(array $file, string $relationName = 'photo', string $path = 'uploads', string $disk = 'public')
    {
        if (!isset($file['base64']) || !isset($file['type'])) {
            Log::warning('Invalid file data', ['file' => $file]);
            return;
        }

        if ($this->$relationName()->exists()) {
            foreach ($this->$relationName as $oldFile) {
                try {
                    Storage::disk($disk)->delete($oldFile->path);
                    $oldFile->delete();
                    Log::info('Foto lama berhasil dihapus', [
                        'path' => $oldFile->path,
                        'fileable_id' => $this->getKey(),
                    ]);
                } catch (\Throwable $e) {
                    Log::error('Gagal menghapus foto lama', [
                        'fileable_id' => $this->getKey(),
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }


        $base64Str = preg_replace('/^data:\w+\/\w+;base64,/', '', $file['base64']);
        $base64Str = str_replace(' ', '+', $base64Str);
        $decoded = base64_decode($base64Str);

        if ($decoded === false) {
            Log::error('Gagal decode base64', ['base64' => substr($base64Str, 0, 30)]);
            return;
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
