<?php

namespace App\Controller\Admin;

use App\Entity\Core\Business;
use App\Entity\Core\User;
use App\Form\AddressType;
use App\Util\Admin\Field\AddressField;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class BusinessCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Business::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // the labels used to refer to this entity in titles, buttons, etc.
            ->setEntityLabelInSingular('Comercio')
            ->setEntityLabelInPlural('Comercios')

            ->setEntityLabelInSingular(
                fn (?Business $entity, ?string $pageName) => $entity ? $entity->getName() : ''
            )
            ->overrideTemplate('crud/edit', 'admin/crud/business_form_edit.html.twig')
            ->overrideTemplate('crud/new', 'admin/crud/business_form_new.html.twig')
            // in addition to a string, the argument of the singular and plural label methods
            // can be a closure that defines two nullable arguments: entityInstance (which will
            // be null in 'index' and 'new' pages) and the current page name

            // the Symfony Security permission needed to manage the entity
            // (none by default, so you can manage all instances of the entity)
            ->setEntityPermission(User::ROLE_BUSINESS)
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            // ...
            ->remove(Crud::PAGE_DETAIL, Action::INDEX)
            ->setPermission(Action::NEW, User::ROLE_ADMIN_WORKER)
            ->setPermission(Action::DELETE, User::ROLE_ADMIN)
            ->setPermission(Action::EDIT, User::ROLE_BUSINESS)
        ;
    }

    
    public function configureFields(string $pageName): iterable
    {
        $fields = [
            TextField::new('name','Nombre'),
            TextField::new('title','descripción breve'),
            TextareaField::new('description','descripción'),
        ];

        if (Crud::PAGE_INDEX != $pageName && Crud::PAGE_DETAIL != $pageName) {
            $fields[] = AddressField::new('address','Dirección');
        }

        return $fields;
    }
    
    public function index(AdminContext $context)
    {
        if ($user = $this->getUser()) {
            if (!$user->isUserAdmin()) {
                $business = $this->getUser()->getYourBusiness() ?? $this->getUser()->getBusiness();
                $url = $this->container->get(AdminUrlGenerator::class)
                    ->setController(BusinessCrudController::class)
                    ->setAction(Action::DETAIL)
                    ->setEntityId($business->getId())
                    ->generateUrl()
                ;

                return $this->redirect($url);
            }
        }
        return parent::index($context);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityInstance->setName(null);
        $entityManager->persist($entityInstance);
        $entityManager->flush();
    }
}
