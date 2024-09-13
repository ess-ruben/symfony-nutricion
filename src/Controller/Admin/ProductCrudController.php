<?php

namespace App\Controller\Admin;

use App\Entity\Commerce\Product;
use App\Entity\Core\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // the labels used to refer to this entity in titles, buttons, etc.
            ->setEntityLabelInSingular('Producto')
            ->setEntityLabelInPlural('Productos')
            ->setEntityPermission(User::ROLE_BUSINESS)
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        
        $actions
            ->setPermission(Action::NEW, User::ROLE_BUSINESS)
            ->setPermission(Action::EDIT, User::ROLE_BUSINESS)
            ->setPermission(Action::DELETE, User::ROLE_ADMIN_WORKER)
        ;

        return $actions;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name','Nombre'),
            NumberField::new('grams','Gramos'),
            NumberField::new('kcal','Kcal'),
            AssociationField::new('listProduct','Categoria'),
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
          ->add(EntityFilter::new("listProduct","Categoria"))
        ;
    }
    
}
