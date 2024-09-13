<?php

namespace App\Controller\Admin;

use App\Entity\Client\Issue;
use App\Entity\Client\IssueResponse;
use App\Entity\Core\User;
use Doctrine\ORM\EntityRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;

class IssueCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Issue::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // the labels used to refer to this entity in titles, buttons, etc.
            ->setEntityLabelInSingular('Incidencia')
            ->setEntityLabelInPlural('Incidencias')
            ->overrideTemplate('crud/detail', 'admin/crud/issue_form_detail.html.twig')
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->setPermission(Action::NEW, User::ROLE_ADMIN)
            ->setPermission(Action::DELETE, User::ROLE_ADMIN)
            ->setPermission(Action::EDIT, User::ROLE_ADMIN_WORKER)
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
          ->add(DateTimeFilter::new("createAt","Fecha"))
          ->add(EntityFilter::new("user","Cliente")
            ->setFormTypeOption(
                'value_type_options.query_builder',
                static fn(EntityRepository $repository) => $repository->getQueryBuilderByRoleLike(User::ROLE_USER)
            )
          )
        ;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('user','Cliente'),
            DateTimeField::new('createAt','Fecha'),
            TextField::new('title','Asunto'),
            TextField::new('body','Contenido')->hideOnIndex(),
            BooleanField::new('isOpen','Abierto')->renderAsSwitch(false),
        ];
    }

    public function detail(AdminContext $context)
    {
        $entity = $context->getEntity()->getInstance();
        if (
            isset($_POST['response']) &&
            !empty($_POST['response']) && 
            !empty($entity)
        ) {
            $response = new IssueResponse();
            $response->setResponse($_POST['response']);
            $response->setIssue($entity);

            $this->persistEntity($this->container->get('doctrine')->getManagerForClass(IssueResponse::class), $response);
            $entity->addIssueResponse($response);
        }
        return parent::detail($context);
    }
    
}
