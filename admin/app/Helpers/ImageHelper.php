<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;

class ImageHelper
{
    /**
     * 업로드된 이미지를 WebP로 변환하여 저장
     *
     * @param  UploadedFile  $file       업로드 파일
     * @param  string        $destDir    저장 디렉터리 절대경로
     * @param  string|null   $filename   파일명(확장자 제외). null 이면 UUID 자동 생성
     * @param  int           $quality    WebP 품질 (0–100)
     * @return string                    저장된 파일의 절대경로
     */
    public static function saveAsWebp(
        UploadedFile $file,
        string $destDir,
        ?string $filename = null,
        int $quality = 85
    ): string {
        // 디렉터리 생성
        if (!is_dir($destDir)) {
            mkdir($destDir, 0755, true);
        }

        $filename = $filename ?? (string) \Illuminate\Support\Str::uuid();
        $destPath = rtrim($destDir, '/') . '/' . $filename . '.webp';

        $mime = $file->getMimeType();
        $src  = $file->getRealPath();

        // 원본 이미지 리소스 생성
        $image = match (true) {
            str_contains($mime, 'jpeg') || str_contains($mime, 'jpg') => imagecreatefromjpeg($src),
            str_contains($mime, 'png')  => self::pngToTrueColor($src),
            str_contains($mime, 'gif')  => imagecreatefromgif($src),
            str_contains($mime, 'webp') => imagecreatefromwebp($src),
            default                     => null,
        };

        // GD로 변환 불가(SVG 등)인 경우 그대로 저장
        if ($image === null || $image === false) {
            $ext      = strtolower($file->getClientOriginalExtension());
            $fallback = rtrim($destDir, '/') . '/' . $filename . '.' . $ext;
            $file->move($destDir, $filename . '.' . $ext);
            return $fallback;
        }

        // WebP로 저장
        imagewebp($image, $destPath, $quality);
        imagedestroy($image);

        return $destPath;
    }

    /**
     * SVG 파일은 그대로 저장 (변환 불필요)
     */
    public static function saveSvg(UploadedFile $file, string $destDir, ?string $filename = null): string
    {
        if (!is_dir($destDir)) {
            mkdir($destDir, 0755, true);
        }
        $filename = ($filename ?? (string) \Illuminate\Support\Str::uuid()) . '.svg';
        $file->move($destDir, $filename);
        return rtrim($destDir, '/') . '/' . $filename;
    }

    /**
     * PNG → truecolor (투명도 보존)
     */
    private static function pngToTrueColor(string $src)
    {
        $src_img = imagecreatefrompng($src);
        if (!$src_img) return false;

        $w = imagesx($src_img);
        $h = imagesy($src_img);

        $dst = imagecreatetruecolor($w, $h);
        // 투명 배경 처리
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
        $transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
        imagefilledrectangle($dst, 0, 0, $w, $h, $transparent);
        imagealphablending($dst, true);

        imagecopy($dst, $src_img, 0, 0, 0, 0, $w, $h);
        imagedestroy($src_img);

        return $dst;
    }

    /**
     * 업로드 파일 종류 자동 판별 후 저장
     * SVG → 그대로, 나머지 → WebP 변환
     */
    public static function upload(UploadedFile $file, string $destDir, int $quality = 85): string
    {
        $ext = strtolower($file->getClientOriginalExtension());
        if ($ext === 'svg') {
            return self::saveSvg($file, $destDir);
        }
        return self::saveAsWebp($file, $destDir, null, $quality);
    }
}
