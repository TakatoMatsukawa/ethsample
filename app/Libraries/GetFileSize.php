<?php

namespace App\Libraries;

use Illuminate\Support\Facades\Storage;

class GetFileSize
{
    /**
     * ファイルのサイズを返却する
     *
     * @param $filePath: string
     * @param $precision: Int
     * @return string
     */
    public function GetFileSize(string $filePath, Int $precision = 2): string
    {

        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        if (Storage::exists($filePath)) {
            $fileSize = Storage::size($filePath);
            $bytes = max($fileSize, 0);
            $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
            $pow = min($pow, count($units) - 1);

            $bytes /= pow(1024, $pow);

            $result = round($bytes, $precision) . $units[$pow];
        } else {
            $result = "Not Found";
        }

        return $result;
    }
}
