<?php

namespace App\Entity\Cms;

use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use App\Repository\Cms\PostEducationRepository;
use App\Util\Interfaces\MediaInterface;
use App\Util\Interfaces\NotifyInterface;
use App\Util\Traits\MediaFileItem;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[Vich\Uploadable]
#[ApiFilter(BooleanFilter::class, properties: ['category.isActive'])]
#[ORM\Entity(repositoryClass: PostEducationRepository::class)]
class PostEducation extends Post implements MediaInterface, NotifyInterface
{
    use MediaFileItem;

    public function getDiscr(){
        return Post::POST_EDUCTATION;
    }
}
