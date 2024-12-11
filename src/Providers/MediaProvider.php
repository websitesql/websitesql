<?php declare(strict_types=1);

namespace WebsiteSQL\WebsiteSQL\Providers;

use WebsiteSQL\WebsiteSQL\App;
use Exception;

class MediaProvider
{
    private App $app;
    private string $mediaBasePath;

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->mediaBasePath = $this->app->getBasePath() . '/public/wsql-contents/uploads';
    }

    public function getMediaPath(): string
    {
        return $this->mediaBasePath;
    }

    /*
     * Get a media file by its ID
     * 
     * @param int $id
     * @return array|null
     */
    public function get(int $id): ?array
    {
        return $this->app->getDatabase()->get($this->app->getStrings()->getTableMedia(), '*', ['id' => $id]);
    }

    /*
     * Upload a media file
     * 
     * @param string $file
     * @param string $name
     * @param int|null $createdBy
     * @return array
     */
    public function upload(string $file, string $name, ?int $createdBy = null): array
    {
        $year = date('Y');
        $month = date('m');
        $directoryPath = $this->mediaBasePath . '/' . $year . '/' . $month;

        if (!is_dir($directoryPath) && !mkdir($directoryPath, 0755, true)) {
            throw new Exception('Failed to create media subdirectories.');
        }

        $name = $this->sanitizeFileName($name);
        $uniqueName = uniqid() . '_' . $name;
        $destinationPath = $directoryPath . '/' . $uniqueName;

        if (is_uploaded_file($file)) {
            if (!move_uploaded_file($file, $destinationPath)) {
                throw new Exception('Failed to move uploaded file.');
            }
        } else {
            if (file_put_contents($destinationPath, file_get_contents($file)) === false) {
                throw new Exception('Failed to write file to media directory.');
            }
        }

        $relativeUrl = '/wsql-contents/uploads/' . $year . '/' . $month . '/' . $uniqueName;
        $fileMetadata = [
            'filename' => $name,
            'mimetype' => mime_content_type($destinationPath),
            'size' => filesize($destinationPath),
            'tag' => "$year/$month",
            'thumbnail' => null,
            'path' => $relativeUrl,
            'created_by' => $createdBy,
        ];

        $this->app->getDatabase()->insert('wsql_media', $fileMetadata);

        $insertId = $this->app->getDatabase()->id();

        if (!$insertId) {
            unlink($destinationPath);
            throw new Exception('Failed to insert file metadata into the database.');
        }

        // Generate thumbnails if the file is an image
        if ($this->isImage($fileMetadata['mimetype'])) {
            $this->generateThumbnails($destinationPath, (int) $insertId, $year, $month, $createdBy);
        }

        return [
            'id' => $insertId,
            'url' => $relativeUrl,
        ];
    }

    /*
     * Delete a media file and its thumbnails by its ID
     * 
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $file = $this->get($id);

        if (!$file) {
            return false;
        }

        $filePath = $this->app->getBasePath() . '/public' . $file['path'];

        // Delete the main file
        if (file_exists($filePath) && !unlink($filePath)) {
            return false;
        }

        // Get the thumbnails associated with the file
        $thumbnails = $this->app->getDatabase()->select(
            $this->app->getStrings()->getTableMedia(),
            ['id', 'path'],
            ['thumbnail' => $id]
        );

        // Delete each thumbnail file and remove its entry from the database
        foreach ($thumbnails as $thumbnail) {
            $thumbnailPath = $this->app->getBasePath() . '/public' . $thumbnail['path'];

            if (file_exists($thumbnailPath) && !unlink($thumbnailPath)) {
                return false;
            }

            // Delete thumbnail from the database
            $this->app->getDatabase()->delete(
                $this->app->getStrings()->getTableMedia(),
                ['id' => $thumbnail['id']]
            );
        }

        // Delete the main file entry from the database
        $this->app->getDatabase()->delete(
            $this->app->getStrings()->getTableMedia(),
            ['id' => $id]
        );

        return true;
    }

    /*
     * Generate thumbnails for an image file
     * 
     * @param string $filePath
     * @param int $fileId
     * @param string $year
     * @param string $month
     * @param int|null $createdBy
     */
    private function generateThumbnails(string $filePath, int $fileId, string $year, string $month, ?int $createdBy): void
    {
        $thumbnailSizes = [
            'small' => [150, 150],
            'medium' => [300, 300],
            'large' => [1024, 768],
        ];

        foreach ($thumbnailSizes as $sizeName => [$width, $height]) {
            $thumbnailPath = $this->resizeImage($filePath, $width, $height, $sizeName);

            if ($thumbnailPath) {
                $relativeUrl = '/wsql-contents/uploads/' . $year . '/' . $month . '/' . basename($thumbnailPath);
                $thumbnailMetadata = [
                    'filename' => basename($thumbnailPath),
                    'mimetype' => mime_content_type($thumbnailPath),
                    'size' => filesize($thumbnailPath),
                    'tag' => "$year/$month",
                    'thumbnail' => $fileId,
                    'path' => $relativeUrl,
                    'created_by' => $createdBy,
                ];

                $db = $this->app->getDatabase();
                $db->insert('wsql_media', $thumbnailMetadata);
            }
        }
    }

    /*
     * Resize an image file
     * 
     * @param string $filePath
     * @param int $width
     * @param int $height
     * @param string $sizeName
     * @return string|null
     */
    private function resizeImage(string $filePath, int $width, int $height, string $sizeName): ?string
    {
        $imageInfo = getimagesize($filePath);
        if (!$imageInfo) {
            return null; // Not an image
        }

        [$originalWidth, $originalHeight, $imageType] = $imageInfo;
        $imageCreateFunction = match ($imageType) {
            IMAGETYPE_JPEG => 'imagecreatefromjpeg',
            IMAGETYPE_PNG => 'imagecreatefrompng',
            IMAGETYPE_GIF => 'imagecreatefromgif',
            default => null,
        };

        if (!$imageCreateFunction || !function_exists($imageCreateFunction)) {
            return null; // Unsupported image type
        }

        $originalImage = $imageCreateFunction($filePath);
        $thumbnailImage = imagecreatetruecolor($width, $height);

        imagecopyresampled(
            $thumbnailImage,
            $originalImage,
            0,
            0,
            0,
            0,
            $width,
            $height,
            $originalWidth,
            $originalHeight
        );

        $thumbnailPath = str_replace(
            basename($filePath),
            pathinfo(basename($filePath), PATHINFO_FILENAME) . "_$sizeName." . pathinfo($filePath, PATHINFO_EXTENSION),
            $filePath
        );

        $imageSaveFunction = match ($imageType) {
            IMAGETYPE_JPEG => 'imagejpeg',
            IMAGETYPE_PNG => 'imagepng',
            IMAGETYPE_GIF => 'imagegif',
        };

        if (!$imageSaveFunction($thumbnailImage, $thumbnailPath)) {
            return null;
        }

        imagedestroy($originalImage);
        imagedestroy($thumbnailImage);

        return $thumbnailPath;
    }

    /*
     * Check if a MIME type is an image
     * 
     * @param string $mimeType
     * @return bool
     */
    private function isImage(string $mimeType): bool
    {
        return str_starts_with($mimeType, 'image/');
    }

    /*
     * Sanitize a file name
     * 
     * @param string $name
     * @return string
     */
    private function sanitizeFileName(string $name): string
    {
        return substr(preg_replace('/[^a-zA-Z0-9\-_\.]/', '_', $name), 0, 255);
    }
}
