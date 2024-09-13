<?php

namespace App\Util\Admin\Filter;

use App\Entity\Country;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FieldDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FilterDataDto;
use EasyCorp\Bundle\EasyAdminBundle\Filter\FilterTrait;
use EasyCorp\Bundle\EasyAdminBundle\Form\Filter\Type\TextFilterType;
use EasyCorp\Bundle\EasyAdminBundle\Form\Filter\Type\EntityFilterType;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Filter\FilterInterface;

class CustomEntityPropertyStringFilter implements FilterInterface
{
    use FilterTrait;

    private $attribute = "name";
    public static function new(string $propertyName, $label = null): self
    {
        $split = explode(".",$propertyName);
        if (count($split) > 1) {
          $propertyName = $split[0];
          //$this->attribute = $split[1];
        }
        return (new self())
            ->setFilterFqcn(__CLASS__)
            ->setProperty($propertyName)
            ->setLabel($label)
            ->setAttribute(count($split) > 1? $split[1]: "name")
            ->setFormType(TextFilterType::class);
    }
    public function setAttribute($attribute): self
    {
      $this->attribute = $attribute;
      return $this;
    }
    public function apply(QueryBuilder $queryBuilder, FilterDataDto $filterDataDto, ?FieldDto $fieldDto, EntityDto $entityDto): void
    {
        $property = $filterDataDto->getProperty();
        $alias = "j$property";
        $property = $filterDataDto->getProperty();
        $comparison = $filterDataDto->getComparison();
        $parameterName = $filterDataDto->getParameterName();

        $queryBuilder->join("entity.$property", $alias)
            ->andWhere(sprintf('%s.%s %s :%s', $alias, $this->attribute, $comparison, $parameterName))
            ->setParameter($parameterName, $filterDataDto->getValue());
    }
}