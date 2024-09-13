<?php

namespace App\Filter\Sql;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

class ActiveSqlFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        
        if (!$targetEntity->reflClass->implementsInterface('\App\Util\Interfaces\ActiveInterface')) {
            return "";
        }
        return $targetTableAlias.'.is_active = '.$this->getParameter('active_sql_filter'); // getParameter applies quoting automatically
    }
}