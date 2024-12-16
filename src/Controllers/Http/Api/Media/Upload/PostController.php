<?php declare(strict_types=1);

namespace WebsiteSQL\WebsiteSQL\Controllers\Http\Api\Media\Upload;

use Exception;
use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\JsonResponse;
use League\Route\Http\Exception\BadRequestException;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use WebsiteSQL\WebsiteSQL\App;

class PostController implements RequestHandlerInterface
{
    /*
     * This object is the main entry point of the application
     * 
     * @var App
     */
    private App $app;

    /*
     * Constructor
     * 
     * @param App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /*
     * Handle the request
     * 
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        try {
            // Extract the uploaded file from the request
            $uploadedFiles = $request->getUploadedFiles();

            // Check if 'file' property exists in the uploaded files
            if (!isset($uploadedFiles['file'])) {
                throw new BadRequestException('File is required in the form-data with the key "file".');
            }

            $file = $uploadedFiles['file'];

            // Manually set the maximum file upload size (e.g., 5MB)
            $maxUploadSize = 5 * 1024 * 1024; // 5MB in bytes

            // Validate the uploaded file
            if ($file->getError() !== UPLOAD_ERR_OK) {
                throw new BadRequestException('File upload error.');
            }

            if ($file->getSize() > $maxUploadSize) {
                throw new BadRequestException('File size exceeds the maximum allowed size of ' . ($maxUploadSize / (1024 * 1024)) . 'MB.');
            }

            $fileName = $file->getClientFilename();
            $fileStream = $file->getStream()->getMetadata('uri');

            // Upload the file via MediaProvider
            $result = $this->app->getMedia()->upload($fileStream, $fileName);

            // Respond with success and the URL of the uploaded file
            return new JsonResponse([
                'success' => true,
                'message' => 'File uploaded successfully.',
                'file' => $result,
            ]);
        } catch (BadRequestException $e) {
            return new JsonResponse(['success' => false, 'message' => $e->getMessage()], 400);
        } catch (Exception $e) {
            return new JsonResponse(['success' => false, 'message' => 'An unexpected error occurred. ' . $e->getMessage()], 500);
        }
    }
}