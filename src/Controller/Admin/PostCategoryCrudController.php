<?php

namespace App\Controller\Admin;

use App\Entity\Core\User;
use App\Entity\Cms\PostCategory;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;

use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class PostCategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PostCategory::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // the labels used to refer to this entity in titles, buttons, etc.
            ->setEntityLabelInSingular('Categoria')
            ->setEntityLabelInPlural('Categorias')
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            // ...
            ->setPermission(Action::NEW, User::ROLE_ADMIN)
            ->setPermission(Action::DELETE, User::ROLE_ADMIN)
            ->setPermission(Action::EDIT, User::ROLE_ADMIN_WORKER)
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
        ;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('name','Nombre'),
            TextField::new('icon','Icono'),
            TextEditorField::new('description','Descripción'),
            BooleanField::new('isActive','Activo')
        ];
    }
    
}
