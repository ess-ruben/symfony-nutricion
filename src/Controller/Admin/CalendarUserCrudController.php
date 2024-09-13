<?php

namespace App\Controller\Admin;

use App\Entity\Core\User;
use App\Entity\Calendar\CalendarUser;
use App\Service\CoreService;
use Doctrine\ORM\EntityRepository;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;

use Vich\UploaderBundle\Form\Type\VichFileType;
use Vich\UploaderBundle\Form\Type\VichImageType;

use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;

use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\NumericFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;

use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FilterConfigDto;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;


class CalendarUserCrudController extends AbstractCrudController
{
    private $coreService;
    public function __construct(CoreService $coreService) {
        $this->coreService = $coreService;
    }

    public static function getEntityFqcn(): string
    {
        return CalendarUser::class;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
          ->add(DateTimeFilter::new("createAt","Fecha"))
          ->add(EntityFilter::new("user","Usuario")
            ->setFormTypeOption(
                'value_type_options.query_builder',
                static fn(EntityRepository $repository) => $repository->getQueryBuilderByRoleLike(User::ROLE_USER)
            )
          )
        ;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // the labels used to refer to this entity in titles, buttons, etc.
            ->setEntityLabelInSingular('Dieta')
            ->setEntityLabelInPlural('Dietas')

            ->setEntityLabelInSingular(
                function (?CalendarUser $entity, ?string $pageName){
                    return ($entity) ? $entity->__toString() : '';
                }
            )
        ;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $queryBuilder = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);

        // if user defined sort is not set
        if (0 === count($searchDto->getSort())) {
            $queryBuilder
                ->addOrderBy('entity.id', 'DESC');
        }

        return $queryBuilder;
    }

    
    public function configureFields(string $pageName): iterable
    {
        $clients = [];
        if (Crud::PAGE_INDEX != $pageName && Crud::PAGE_DETAIL != $pageName) {
            $repository = $this->coreService->getRepository(User::class);
            foreach ($repository->findByRole(User::ROLE_USER) as $u) {
                $clients[$u->__toString()] = $u;
            }
        }

        return [
            Field::new('id','Id')->onlyOnIndex(),
            AssociationField::new('user','Usuario')->hideOnForm(),
            DateTimeField::new('createAt','Fecha')->setFormat('medium')->hideOnForm(),
            ChoiceField::new('user','Usuario')
                ->setChoices($clients)
                ->onlyOnForms()
            ,
            TextField::new('fileName','PDF')
                ->setTemplatePath('admin/pdf.html.twig')
                ->hideOnForm()
            ,
            Field::new('file','PDF')
                ->setFormType(VichFileType::class)
                ->onlyOnForms()
                ->setFormTypeOptions(
                    [
                      'download_label' => 'Link de descarga'
                    ]
                )
            ,
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        
        $newDiet = Action::new('newDiet', 'Nueva dieta')
            ->linkToCrudAction('newDiet')
            //->displayIf(static function ($entity) {
            //    return $entity->isClient();
            //})
        ;  
        
        return $actions
            ->add(Crud::PAGE_DETAIL,$newDiet)
            ->setPermission(Action::DELETE, User::ROLE_ADMIN)
        ;
    }

    public function newDiet(AdminContext $context)
    {
        $entity = $context->getEntity()->getInstance();
        
        $calendar = new CalendarUser();
        $calendar->setUser($entity->getUser());

        $this->persistEntity($this->container->get('doctrine')->getManagerForClass($context->getEntity()->getFqcn()), $calendar);


        $url = $this->container->get(AdminUrlGenerator::class)
          ->setController(self::class)
          ->setAction(Action::DETAIL)
          ->setEntityId($calendar->getId())
          ->generateUrl();

        return $this->redirect($url);
    }
    
}
