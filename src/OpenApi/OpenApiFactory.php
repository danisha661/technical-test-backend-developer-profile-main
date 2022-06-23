<?php

declare(strict_types=1);

namespace App\OpenApi;

use ApiPlatform\Core\OpenApi\OpenApi;
use ApiPlatform\Core\OpenApi\Model\PathItem;
use ApiPlatform\Core\OpenApi\Model\Operation;
use ApiPlatform\Core\OpenApi\Model\Parameter;
use Symfony\Component\HttpFoundation\Response;
use ApiPlatform\Core\OpenApi\Model\RequestBody;
use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;

class OpenApiFactory implements OpenApiFactoryInterface
{
    public const OPEN_API_CONTEXT_HIDDEN = 'hidden';
    public const OPEN_API_TAG_WITHOUT_IDENTIFIER = '#withoutIdentifier';

    public function __construct(private OpenApiFactoryInterface $decorated)
    {}

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = $this->decorated->__invoke($context);
        
        /** @var PathItem $path */
        foreach ($openApi->getPaths()->getPaths() as $key => $path) {
            if ($path->getGet() && $path->getGet()->getSummary() === self::OPEN_API_CONTEXT_HIDDEN) {
                $openApi->getPaths()->addPath($key, $path->withGet(null));
            }
        }

        $securitySchemes = $openApi->getComponents()->getSecuritySchemes();

        $securitySchemes['bearerAuth'] = new \ArrayObject([
            'type' => 'http',
            'scheme'   => 'bearer',
            'bearerFormat' => 'JWT'
        ]);

        $schemas = $openApi->getComponents()->getSchemas();
        $schemas['Credentials'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'username' => [
                    'type' => 'string',
                    'example' => 'john@doe.fr'
                ],
                'password' => [
                    'type' => 'string',
                    'example' => '12345678'
                ]
            ]
        ]);
        $schemas['Token'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'token' => [
                    'type' => 'string',
                    'readOnly' => true
                ]
            ]
        ]);

        $openApi = $this->resourceWithoutIdentifier($openApi);

        $openApi->getPaths()->addPath('/api/login', $this->getPostApiLoginPath());
        $openApi->getPaths()->addPath('/logout', $this->getPostApiLogoutPath());

        return $openApi;
    }

    private function getPostApiLoginPath(): PathItem
    {
        return new PathItem(
            post: new Operation(
                operationId: 'postApiLogin',
                tags: ['User'],
                requestBody: new RequestBody(
                    content: new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                '$ref' => '#/components/schemas/Credentials'
                            ]
                        ]
                    ])
                ),
                responses: [
                    Response::HTTP_OK => [
                        'description' => 'Token JWT',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/Token'
                                ],
                            ],
                        ],
                    ]
                ]
            )
        );
    }

    private function getPostApiLogoutPath(): PathItem
    {
        return new PathItem(
            post: new Operation(
                operationId: 'postApiLogout',
                tags: ['User'],
                responses: [
                    Response::HTTP_NO_CONTENT => []
                ]
            )
        );
    }

    private function resourceWithoutIdentifier(OpenApi $openApi): OpenApi
    {
        foreach ($openApi->getPaths()->getPaths() as $path => $pathItem) {
            // remove identifier parameter from operations which include "#withoutIdentifier" in the description
            foreach (PathItem::$methods as $method) {
                $getter = 'get' . ucfirst(strtolower($method));
                $setter = 'with' . ucfirst(strtolower($method));
                
                /** @var Operation $operation */
                $operation = $pathItem->$getter();
                
                if (
                    $operation 
                    && preg_match(sprintf("/%s/", self::OPEN_API_TAG_WITHOUT_IDENTIFIER), $operation->getDescription())
                ) {
                    /** @var Parameter[] $parameters */
                    $parameters = $operation->getParameters();
                    foreach ($parameters as $i => $parameter) {
                        if (preg_match('/identifier/i', $parameter->getDescription())) {
                            unset($parameters[$i]);
                            break;
                        }
                    }

                    $description = str_replace(self::OPEN_API_TAG_WITHOUT_IDENTIFIER, '', $operation->getDescription());
                    $openApi->getPaths()->addPath(
                        $path, 
                        $pathItem = $pathItem->$setter(
                            $operation->withDescription($description)->withParameters(array_values($parameters))
                        )
                    );
                }
            }
        }

        return $openApi;
    }
}
