<?php declare(strict_types=1);

namespace WebsiteSQL\WebsiteSQL\Controllers\Api\Settings\Branding;

use Exception;
use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\JsonResponse;
use League\Route\Http\Exception\BadRequestException;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use WebsiteSQL\WebsiteSQL\App;

class PatchController implements RequestHandlerInterface
{
    /**
     * The main entry point of the application
     *
     * @var App
     */
    private App $app;

    /**
     * Constructor
     *
     * @param App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * Handle the request
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        try {
            // Get the parsed json data
            $data = json_decode($request->getBody()->getContents(), true);

            // Validate the action and data properties
            if (!isset($data['action'])) {
                throw new BadRequestException('Invalid request data: action is required.');
            }

            switch ($data['action']) {
                case 'add_dm_logo':
                case 'add_lm_logo':
                    $this->handleAddLogo($data['action'], $data['data'] ?? []);
                    break;

                case 'remove_dm_logo':
                case 'remove_lm_logo':
                    $this->handleRemoveLogo($data['action']);
                    break;

                case 'update_name':
                    $this->handleUpdateName($data['data'] ?? []);
                    break;

                default:
                    throw new BadRequestException('Invalid action.');
            }

            return new EmptyResponse(200);
        } catch (BadRequestException $e) {
            return new JsonResponse(['success' => false, 'message' => $e->getMessage()], 400);
        } catch (Exception $e) {
            return new JsonResponse(['success' => false, 'message' => 'An unexpected error occurred. ' . $e->getMessage()], 500);
        }
    }

    /**
     * Handle the addition of logos (light and dark)
     *
     * @param string $action
     * @param array $logoData
     * @throws BadRequestException
     */
    private function handleAddLogo(string $action, array $logoData): void
    {
        $name = $action === 'add_lm_logo'
            ? 'interface.application_lm_logo'
            : 'interface.application_dm_logo';

        $existingRecord = $this->app->getDatabase()->get($this->app->getStrings()->getTableCustomizations(), '*', [
            'name' => $name,
            'user' => null
        ]);

        if ($existingRecord) {
            throw new BadRequestException("A logo already exists for {$name}, please delete the existing logo first.");
        }

        if (!isset($logoData['type']) || !in_array($logoData['type'], ['media', 'image'])) {
            throw new BadRequestException('Invalid logo type.');
        }

        $value = $this->buildLogoValue($logoData);

        // Insert into customizations table
        $this->app->getDatabase()->insert($this->app->getStrings()->getTableCustomizations(), [
            'name' => $name,
            'value' => json_encode($value),
            'user'  => null
        ]);
    }

    /**
     * Handle the removal of logos (light and dark)
     *
     * @param string $action
     * @throws BadRequestException
     */
    private function handleRemoveLogo(string $action): void
    {
        try {
            $name = $action === 'remove_lm_logo'
            ? 'interface.application_lm_logo'
            : 'interface.application_dm_logo';

            // Get the existing record
            $existingRecord = $this->app->getDatabase()->get($this->app->getStrings()->getTableCustomizations(), '*', [
                'name' => $name,
                'user' => null
            ]);

            // Check if the record exists
            if (!$existingRecord) {
                throw new BadRequestException("No logo found for {$name}.");
            }

            // Delete the media if the logo type is media
            $value = json_decode($existingRecord['value'], true);
            if ($value['type'] === 'media') {
                $this->app->getMedia()->delete((int) $value['id']);
            }

            // Delete the record from the customizations table
            $this->app->getDatabase()->delete($this->app->getStrings()->getTableCustomizations(), [
                'name' => $name,
                'user' => null
            ]);
        } catch (Exception $e) {
            throw new BadRequestException('Failed to remove the logo=: ' . $e->getMessage());
        }
    }

    /**
     * Handle the update of application name
     *
     * @param array $nameData
     * @throws BadRequestException
     */
    private function handleUpdateName(array $nameData): void
    {
        if (!isset($nameData['application_name'])) {
            throw new BadRequestException('Application name is required.');
        }

        $name = 'interface.application_name';

        if (empty($nameData['application_name'])) {
            // Delete the existing record if application_name is empty or not set
            $this->app->getDatabase()->delete($this->app->getStrings()->getTableCustomizations(), [
                'name' => $name,
                'user' => null
            ]);
            return;
        }

        $existingRecord = $this->app->getDatabase()->get($this->app->getStrings()->getTableCustomizations(), '*', [
            'name' => $name,
            'user' => null
        ]);

        if ($existingRecord) {
            // Update existing record
            $this->app->getDatabase()->update($this->app->getStrings()->getTableCustomizations(), [
                'value' => $nameData['application_name']
            ], [
                'name' => $name,
                'user' => null
            ]);
        } else {
            // Insert new record
            $this->app->getDatabase()->insert($this->app->getStrings()->getTableCustomizations(), [
                'name' => $name,
                'value' => $nameData['application_name'],
                'user' => null
            ]);
        }
    }

    /**
     * Build the logo value to be inserted
     *
     * @param array $logoData
     * @return array
     * @throws BadRequestException
     */
    private function buildLogoValue(array $logoData): array
    {
        switch ($logoData['type']) {
            case 'media':
                if (!isset($logoData['id'])) {
                    throw new BadRequestException('Media ID is required for media type logo.');
                }
                return [
                    'type' => 'media',
                    'id' => $logoData['id']
                ];
            case 'image':
                if (!isset($logoData['url'])) {
                    throw new BadRequestException('Image URL is required for image type logo.');
                }
                return [
                    'type' => 'image',
                    'url' => $logoData['url']
                ];
            default:
                throw new BadRequestException('Invalid logo type.');
        }
    }
}