<?php

namespace App\Traits;

use Illuminate\{
    Http\Request,
    Support\Facades\Storage,
    Support\Str
};

trait AsignFile
{
        private function assignFields($model, Request $req, array $fields)
    {
        foreach ($fields as $field) {
            if ($req->filled($field)) {
                $model->{$field} = $req->{$field};
            }
        }
    }

    private function saveBase64File($base64Json, $folder, array $allowedTypes, $oldPath = null)
    {
        $fileData = json_decode($base64Json, true);
        $mimeType = $fileData['type'] ?? '';
        $base64Content = $fileData['data'] ?? '';

        if (!$mimeType || !$base64Content) {
            throw new \Exception('Format file tidak valid.');
        }

        $extension = explode('/', $mimeType)[1];

        if (!in_array($extension, $allowedTypes)) {
            throw new \Exception("File harus berupa: " . implode(', ', $allowedTypes));
        }

        $decoded = base64_decode($base64Content);
        if ($decoded === false) {
            throw new \Exception('Gagal decode file.');
        }

        if ($oldPath && Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }

        $fileName = $folder . '/' . Str::random(10) . '.' . $extension;
        Storage::disk('public')->put($fileName, $decoded);

        return $fileName;
    }

}
