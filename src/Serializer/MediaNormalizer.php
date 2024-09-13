<?php

namespace App\Serializer;

use App\Entity\MediaObject;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Vich\UploaderBundle\Storage\StorageInterface;
use App\Util\Interfaces\MediaInterface;

final class MediaNormalizer implements ContextAwareNormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    private const ALREADY_CALLED = 'MEDIA_OBJECT_NORMALIZER_ALREADY_CALLED';

    public function __construct(private StorageInterface $storage)
    {
    }

    public function normalize($object, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $context[self::ALREADY_CALLED] = true;

        //$context[$this->getContextName($object)] = true;
        if (isset($context['groups']) && !array_search('read:media',$context['groups'])) {
          $context['groups'][] = 'read:media';
        }

        foreach ($object->getSerializerFields() as $fileAttr => $urlAttr) {
          $contentUrl = $this->storage->resolveUri($object, $fileAttr);
          $object->setCompleteUrl($contentUrl,$urlAttr);
        }

        return $this->normalizer->normalize($object, $format, $context);
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        if (isset($context[self::ALREADY_CALLED])) {
            return false;
        }

        return $data instanceof MediaInterface;
    }
}