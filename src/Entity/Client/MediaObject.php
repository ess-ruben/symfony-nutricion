<?php

namespace App\Entity\Client;

use App\Controller\Api\CreateMediaObjectAction;
use App\Util\Processor\SaveMediaObject;
use App\Util\Traits\MediaFileItem;
use App\Util\Interfaces\MediaInterface;
use App\Repository\Client\MediaObjectRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: MediaObjectRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['read:media']],
    collectionOperations: [
        'post' => [
            'security' => "is_granted('ROLE_USER')",
            'inputFormats'=> ['multipart' => ['multipart/form-data']],
            'controller' => CreateMediaObjectAction::class,
            'deserialize'=> false,
            'openapiContext' => [
                'requestBody' => [
                    'content' => [
                        'multipart/form-data' => [
                            'schema' => [
                                'type' => 'object', 
                                'properties' => [
                                    'file' => [
                                        'type' => 'string', 
                                        'format' => 'binary'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]
)]
class MediaObject implements MediaInterface
{
    use MediaFileItem;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }
}
