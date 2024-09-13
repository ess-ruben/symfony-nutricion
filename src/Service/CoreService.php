<?php

namespace App\Service;

use App\Entity\Core\User;
use App\Entity\Core\Business;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CoreService {

    private $em;
    private $token;
    private $logger;

    public function __construct(
        TokenStorageInterface $token,
        EntityManagerInterface $entityManager,
        LoggerInterface $loggerInterface,
    )
    {
        $this->em = $entityManager;
        $this->token = $token;
        $this->logger = $loggerInterface;
    }

    public function getLogger() : LoggerInterface
    {
        return $this->logger;
    }

    public function getUser(): ?User 
    {
        $token = $this->token->getToken();
        if ($token) {
            return $token->getUser();
        }  
        return null;
    }

    public function getBusiness(): ?Business
    {
        $user = $this->getUser();
        if (empty($user)) {
            return null;
        }

        return $user->getYourBusiness() ?? $user->getBusiness();
    }

    public function getClientsBussines() : array
    {
      $business = $this->getBusiness();
      if (empty($business)) {
        return [];
      }

      $users = $this->em->getRepository(User::class)->getQueryBuilderByRoleLike(User::ROLE_USER)->getQuery()->getResult();
      return array_values($users);
    }

    public function getEntityManager()
    {
        return $this->em;
    }

    public function getRepository($class)
    {
        return $this->em->getRepository($class);
    }

    public function getQueryBuilderClass(string $class,string $alias = 'e')
    {
        return $this->getRepository($class)->createQueryBuilder($alias);
    }

    public function joinBuilderTable(
        QueryBuilder $builder,
        string $newAlias,
        string $field,
        string $joinType = null, 
        $conditionType = null, 
        $condition = null
    )
    {
        if (!in_array($newAlias, $builder->getAllAliases()))
        {
            switch ($joinType) {
                case Expr\Join::LEFT_JOIN:
                    $builder->leftJoin($field, $newAlias,$conditionType,$condition);
                    break;
                default:
                    $builder->join($field, $newAlias,$conditionType,$condition);
                    break;
            }
        }
    }
      
}
