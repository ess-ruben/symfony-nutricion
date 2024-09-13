<?php

namespace App\Filter\Sql;

use App\Entity\Core\Business;
use App\Entity\Core\User;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

class BusinessSqlFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        $bussinesId = $this->getParameter('business_sql_filter');
        // Ejemplo: Supongamos que quieres filtrar las filas de la tabla "product" donde el campo "status" sea igual a "active"
        if ($targetEntity->getReflectionClass()->name === Business::class) {
            return $targetTableAlias . '.id = ' . $bussinesId;
        }

        if ($targetEntity->getReflectionClass()->name === User::class) {
            $bossId = $this->getParameter('user_boss_sql_filter');
            return "$targetTableAlias.business_id = $bussinesId OR $targetTableAlias.id = $bossId";
        }

        // Si no quieres aplicar el filtro a una entidad específica, simplemente devuelve null
        return '';
    }
}