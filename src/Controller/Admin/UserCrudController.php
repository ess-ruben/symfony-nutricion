<?php

namespace App\Controller\Admin;

use App\Entity\Core\User;
use App\Service\UserService;

use App\Util\Enum\UserStatus;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;

use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;

use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FilterConfigDto;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;

class UserCrudController extends AbstractCrudController
{
    private $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // the labels used to refer to this entity in titles, buttons, etc.
            ->setEntityLabelInSingular('Usuario')
            ->setEntityLabelInPlural('Usuarios')
            // the Symfony Security permission needed to manage the entity
            // (none by default, so you can manage all instances of the entity)
            ->setEntityPermission(User::ROLE_BUSINESS_WORKER)
        ;
    }

    public function dietUser(AdminContext $context)
    {
        $entity = $context->getEntity()->getInstance();
        $calendar = $this->userService->checkCalendarUser($entity);

        $url = $this->container->get(AdminUrlGenerator::class)
          ->setController(CalendarUserCrudController::class)
          ->setAction(Action::DETAIL)
          ->setEntityId($calendar->getId())
          ->generateUrl();

        return $this->redirect($url);
    }

    public function configureActions(Actions $actions): Actions
    {
        $dietUser = Action::new('dietUser', 'Dieta')
            ->linkToCrudAction('dietUser')
            ->displayIf(static function ($entity) {
                return $entity->isClient();
            })
        ;
        
        return $actions
        // ...
            ->add(Crud::PAGE_INDEX,$dietUser)
            ->setPermission(Action::DELETE, User::ROLE_ADMIN)
        ;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $response = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);
        
        
        $currentUser = $this->getUser();
        if(!$currentUser->isUserAdmin() && !$currentUser->isBossBusiness()) {
            $this->userService->coreService->getRepository(User::class)->setQueryByRole($response,User::ROLE_USER,'entity');
        }

        return $response;
    }
    
    public function configureFields(string $pageName): iterable
    {
        $currentUser = $this->getUser();

        $rolesField = ChoiceField::new("role", "Tipo usuario");
        $roles = [
            "Cliente" => User::ROLE_USER
        ];

        foreach ($currentUser->getRoles() as $role) {
            switch ($role) {
                case User::ROLE_ADMIN:
                    $roles["Trabajador"] = User::ROLE_ADMIN_WORKER;
                    $roles["Administrador"] = User::ROLE_ADMIN;
                case User::ROLE_ADMIN_WORKER:
                    $roles["Empresa"] = User::ROLE_BUSINESS;
                case User::ROLE_BUSINESS:
                    $roles["Trabajador Empresa"] = User::ROLE_BUSINESS_WORKER;
                    $roles["Usuario Administrativo"] = User::ROLE_BUSINESS_ADMINISTRATION;
                    break;
            }
        }
        
        $rolesField->setChoices($roles);

        return [
            Field::new('id','Id')->hideOnForm(),
            Field::new('email', "Email"),
            Field::new('name', "Nombre"),
            Field::new('surnames', "Apellidos"),
            BooleanField::new('active', "Activo"),
            Field::new('plain_password', "Contraseña")->hideOnIndex(),
            ChoiceField::new('gender','Genero')
                ->setChoices([
                    "Masculino" => UserStatus::GENDER_MALE,
                    "Femenino" => UserStatus::GENDER_FEMALE,
                ])
                ->onlyOnForms()
            ,
            NumberField::new('tall','Altura en cm')->onlyOnForms(),
            $rolesField->hideOnIndex()
        ];
    }
    
}
