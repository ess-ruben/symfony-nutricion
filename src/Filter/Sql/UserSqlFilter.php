<?php

namespace App\Filter\Sql;

use App\Entity\Client\IssueResponse;
use App\Entity\Core\User;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

class UserSqlFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        if (!$targetEntity->reflClass->implementsInterface('\App\Util\Interfaces\UserInterface')) {
            return "";
        }
        if ($targetEntity->reflClass->isInstance(new IssueResponse())){
            return "";
        }
        return $targetTableAlias.'.user_id = '.$this->getParameter('user_sql_filter'); // getParameter applies quoting automatically
    }
}