<?php

namespace App\Controller\Admin;

use App\Entity\Calendar\Meeting;
use App\Entity\Core\User;
use App\Service\MeetingService;
use App\Util\Enum\MeetingStatus;

use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;

use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\NumericFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FilterConfigDto;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;


class MeetingCrudController extends AbstractCrudController
{
    private $workers = [];
    private $meetingService;

    public function configureActions(Actions $actions): Actions
    {
        
        $actions
            ->setPermission(Action::NEW, User::ROLE_BUSINESS_ADMINISTRATION)
            ->setPermission(Action::EDIT, User::ROLE_BUSINESS)
            ->setPermission(Action::DELETE, User::ROLE_ADMIN)
        ;

        $user = $this->getUser();
        if ($user->isAdministrationBusiness()) {
            return $actions;
        }

        $measure = Action::new('measure', 'Tomar Medida')
            ->linkToCrudAction('measure')
            ->displayIf(static function (Meeting $entity) {
                return $entity->getStatus() == MeetingStatus::PENDING;
            })
        ;

        return $actions
            ->add(Crud::PAGE_INDEX,$measure)
        ;
    }

    public function __construct(MeetingService $meetingService)
    {
        $this->meetingService = $meetingService;
        $user = $meetingService->coreService->getUser();

        $users = $user->isUserAdmin() ? $meetingService->getAllBussinesUser() : $meetingService->getWorkersBussines();
        foreach ($users as $user) {
            $this->workers[$user->__toString()] = $user;
        }
    }
    
    public static function getEntityFqcn(): string
    {
        return Meeting::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDefaultSort(['dateAt'=>'ASC'])
            // the labels used to refer to this entity in titles, buttons, etc.
            ->setEntityLabelInSingular('Cita')
            ->setEntityLabelInPlural('Citas')

            ->setEntityLabelInSingular(
                function (?Meeting $entity, ?string $pageName){
                    return ($entity) ? $entity->__toString() : '';
                }
            )
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
          ->add(DateTimeFilter::new("dateAt","Fecha"))
          ->add(EntityFilter::new("user","Cliente")
            ->setFormTypeOption(
                'value_type_options.query_builder',
                static fn(EntityRepository $repository) => $repository->getQueryBuilderByRoleLike(User::ROLE_USER)
            )
          )
          ->add(ChoiceFilter::new("creator","Trabajador")
            ->setChoices(
                $this->workers
            )
          )
          ->add(ChoiceFilter::new("status","Estado")
            ->setChoices(
                MeetingStatus::VALUES
            )
          )
        ;
    }

    
    public function configureFields(string $pageName): iterable
    {
        $clients = [];
        if (Crud::PAGE_INDEX != $pageName && Crud::PAGE_DETAIL != $pageName) {
            $repository = $this->meetingService->coreService->getRepository(User::class);
            foreach ($repository->findByRole(User::ROLE_USER) as $u) {
                $clients[$u->__toString()] = $u;
            }
        }
        
        
        $fields = [
            AssociationField::new('user','Usuario')->hideOnForm(),
            ChoiceField::new('user','Usuario')
                ->setChoices($clients)
                ->onlyOnForms()
            ,
            DateTimeField::new('dateAt','Fecha')
        ];

        $user = $this->meetingService->coreService->getUser();
        $fields[] = AssociationField::new('creator','Trabajador')->hideOnForm();
        if (!$user->isWorkerBusiness()) {
            $fields[] = ChoiceField::new('creator','Trabajador')
                ->setChoices($this->workers)
                ->hideOnIndex()
                ->hideOnDetail()
            ;
        }
        
        $fields[] = ChoiceField::new('status','Estado')
            ->setChoices(MeetingStatus::VALUES)
            ->hideWhenCreating()
        ;
        
        return $fields;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $response = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);

        if (!empty($_GET) && !isset($_GET["filters"])) {
            $now = new \DateTime('now');
            $now->setTime(0,0,0);
            $response
                ->andWhere('entity.dateAt >= :date')
                ->setParameter('date',new \DateTime('now'))
            ;
        }

        return $response;
    }

    public function measure(AdminContext $context)
    {
        $entity = $context->getEntity()->getInstance();

        $url = $this->container->get(AdminUrlGenerator::class)
            ->unsetAll()
            ->setController(UserMeasureCrudController::class)
            ->setAction(Action::NEW)
            ->set('meeting',$entity->getId())
            ->generateUrl()
        ;

        return $this->redirect($url);
    }
    
}
