<?php declare(strict_types=1);

namespace WebsiteSQL\WebsiteSQL\Controllers\Http\Api\Settings\Branding;

use Exception;
use Laminas\Diactoros\Response\EmptyResponse;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\JsonResponse;
use WebsiteSQL\WebsiteSQL\App;

class GetController implements RequestHandlerInterface
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
        $branding = $this->fetchBranding();
        $response = $this->buildResponse($branding);

        return new JsonResponse($response, 200, [
            'Content-Type' => 'application/json'
        ]);
    }

    /**
     * Fetch branding data from the database
     *
     * @return array
     */
    private function fetchBranding(): array
    {
        return $this->app->getDatabase()->select(
            $this->app->getStrings()->getTableCustomizations(),
            [
                'id',
                'user',
                'name',
                'value',
                'created_at',
                'created_by',
            ],
            [
                'AND' => [
                    'name' => ['interface.application_lm_logo', 'interface.application_dm_logo', 'interface.application_name'],
                    'user' => null,
                ],
            ]
        );
    }

    /**
     * Build the response based on branding data
     *
     * @param array $branding
     * @return array
     */
    private function buildResponse(array $branding): array
    {
        $response = [
            'status' => 'success',
            'data' => [
                'application_name' => null,
                'logo' => [
                    'light' => null,
                    'dark' => null,
                ],
            ]
        ];

        foreach ($branding as $row) {
            $this->processBrandingRow($row, $response);
        }

        return $response;
    }

    /**
     * Process a single branding row and update the response accordingly
     *
     * @param array $row
     * @param array &$response
     * @return void
     */
    private function processBrandingRow(array $row, array &$response): void
    {
        switch ($row['name']) {
            case 'interface.application_lm_logo':
                $response['data']['logo']['light'] = $this->parseLogoData($row['value']);
                break;

            case 'interface.application_dm_logo':
                $response['data']['logo']['dark'] = $this->parseLogoData($row['value']);
                break;

            case 'interface.application_name':
                $response['data']['application_name'] = $row['value'];
                break;
        }
    }

    /**
     * Parse logo data from the JSON value
     *
     * @param string $value
     * @return array|null
     */
    private function parseLogoData(string $value): ?array
    {
        $data = json_decode($value, true);
        if (!is_array($data) || !isset($data['type'])) {
            return null;
        }

        $rowResponse = [
            'type' => $data['type'],
            'data' => []
        ];

        switch ($data['type']) {
            case 'image':
                $rowResponse['data'] = [
                    'url' => $data['url'],
                ];
                break;
            case 'media':
                $file = $this->app->getMedia()->get((int) $data['id']);
                $rowResponse['data'] = [
                    'url' => $file['path'] ?? null,
                ];
                break;
        }

        return $rowResponse;
    }
}
