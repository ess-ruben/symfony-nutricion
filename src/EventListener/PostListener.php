<?php

namespace App\EventListener;

use App\Entity\Cms\Post;
use App\Entity\Cms\PostCategory;
use App\Service\CoreService;
use App\Util\Interfaces\BusinessInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;

/**
 * The user item listener listens for items using the App\Util\Interfaces\UserInterface trait.
 * It sets the user of the object depending on the current user
 *
 * @author ruben
 */
class PostListener
{

    /**
    * @var CoreService
    */
    private $coreService;

    public function __construct(CoreService $coreService)
    {
        $this->coreService = $coreService;
    }

    /**
    * @ORM\PrePersist
    */
    public function prePersist(LifecycleEventArgs $event)
    {
        $entity = $event->getObject();
        if($entity instanceof Post)
        {
            $repo = $this->coreService->getRepository(PostCategory::class);
            $category = $repo->findOneBy(['type' => $entity->getDiscr()]);
            $entity->setCategory($category);
        }
    }
}