<?php
namespace App\Decorators;

use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\Core\OpenApi\OpenApi;
use ApiPlatform\Core\OpenApi\Model;

/**
* Service for issuing JWT tokens.
**/
final class JwtDecorator implements OpenApiFactoryInterface
{

    private $decorated;

    public function __construct(OpenApiFactoryInterface $decorated) {
      $this->decorated = $decorated;
    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = ($this->decorated)($context);
        $schemas = $openApi->getComponents()->getSchemas();

        $schemas['Token'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'token' => [
                    'type' => 'string',
                    'readOnly' => true,
                ],
                'user' => [
                    'type' => 'object',
                    'readOnly' => true,
                    'properties' => [
                        'email' => [
                            'type' => 'string',
                            'readOnly' => true,
                        ],
                        'id' => [
                            'type' => 'number',
                            'readOnly' => true,
                        ],
                    ]
                ],
            ],
        ]);
        $schemas['Credentials'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'email' => [
                    'type' => 'string',
                    'example' => 'admin@gmail.com',
                ],
                'password' => [
                    'type' => 'string',
                    'example' => '1234',
                ],
            ],
        ]);

        $pathItem = new Model\PathItem(
            'JWT Token',
            'Get JWT token to login.',
            '',
            null,
            null,
             new Model\Operation(
                'postCredentialsItem',
                ['Token'],
                [
                    '200' => [
                        'description' => 'Get JWT token',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/Token',
                                ],
                            ],
                        ],
                    ],
                ],
                "",
                "",
                null,
                [],
                new Model\RequestBody(
                    'Generate new JWT Token',
                    new \ArrayObject([
                      'application/json' => [
                            'schema' => [
                                '$ref' => '#/components/schemas/Credentials',
                            ],
                        ],
                    ]),
                ),
            ),
        );
        $openApi->getPaths()->addPath('/authentication_token', $pathItem);

        return $openApi;
    }
}