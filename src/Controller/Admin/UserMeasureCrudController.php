<?php

namespace App\Controller\Admin;

use App\Entity\Calendar\Meeting;
use App\Entity\Client\UserMeasure;
use App\Entity\Core\User;
use App\Service\CoreService;
use App\Util\Admin\Filter\CustomEntityPropertyStringFilter;
use App\Util\Enum\UserStatus;
use Doctrine\ORM\EntityManagerInterface;

use Doctrine\ORM\EntityRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use Vich\UploaderBundle\Form\Type\VichFileType;

class UserMeasureCrudController extends AbstractCrudController
{
    private $coreService;
    public function __construct(CoreService $cs) {
        $this->coreService = $cs;
    }
    public static function getEntityFqcn(): string
    {
        return UserMeasure::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // the labels used to refer to this entity in titles, buttons, etc.
            ->setEntityLabelInSingular('Medida')
            ->setEntityLabelInPlural('Medidas')

            ->setEntityLabelInSingular(
                function (?UserMeasure $entity, ?string $pageName){
                    $title  = "Medida";
                    if ($entity && $entity->getUser()) {
                        $title = "$title: ".$entity->getUser()->getName();
                    }
                    return $title;
                }
            )
        ;
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

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            // ...
            ->remove(Crud::PAGE_NEW, Action::SAVE_AND_ADD_ANOTHER)
            ->remove(Crud::PAGE_DETAIL, Action::INDEX)
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->setPermission(Action::NEW,User::ROLE_BUSINESS_WORKER)
            ->setPermission(Action::DELETE, User::ROLE_ADMIN)
            ->setPermission(Action::EDIT, User::ROLE_BUSINESS_WORKER)
            ->setPermission(Action::INDEX, User::ROLE_BUSINESS_WORKER)
            ->setPermission(Action::DETAIL, User::ROLE_BUSINESS_WORKER)
        ;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
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
            FormField::addPanel('Fisico'),
            NumberField::new('years','Edad')->onlyOnForms(),
            ChoiceField::new('gender','Genero')
                ->setChoices([
                    "Masculino" => UserStatus::GENDER_MALE,
                    "Femenino" => UserStatus::GENDER_FEMALE,
                ])
                ->onlyOnForms()
            ,
            //NumberField::new('gender','Genero')->onlyOnForms(),
            NumberField::new('height','Altura en cm')->onlyOnForms(),
            NumberField::new('weight','Peso')->onlyOnForms(),
            FormField::addPanel('Medidas'),
            NumberField::new('tricipital','Tricipital')->onlyOnForms(),
            NumberField::new('subscapularis','Subscapularis')->onlyOnForms(),
            NumberField::new('bicipital','Bicipital')->onlyOnForms(),
            NumberField::new('iliacCrest','Cresta Iliaca')->onlyOnForms(),
            NumberField::new('suprailiac','Suprailíaco')->onlyOnForms(),
            NumberField::new('abdominal','Abdominal')->onlyOnForms(),
            NumberField::new('frontThigh','Muslo frontal')->onlyOnForms(),
            NumberField::new('medialCalf','Pantorrilla medial')->onlyOnForms(),

            FormField::addPanel('Perimetros')->onlyOnForms(),
            NumberField::new('waist','Cintura')->onlyOnForms(),
            NumberField::new('hip','Cadera')->onlyOnForms(),
            NumberField::new('thigh','Muslo')->onlyOnForms(),
            NumberField::new('calf','Pantorilla')->onlyOnForms(),
            NumberField::new('contractedArm','Brazo contraido')->onlyOnForms(),
            NumberField::new('relaxedArm','Brazo relajado')->onlyOnForms(),

            AssociationField::new('user','Usuario')->hideOnForm(),
            DateTimeField::new('createAt','Fecha')->hideOnForm(),
        ];
    }
    

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (isset($_GET['meeting'])) {
            $meet = $this->coreService->getRepository(Meeting::class)->find($_GET['meeting']);
            if ($meet) {
                $entityInstance->setMeeting($meet);
            }
        }
        $entityManager->persist($entityInstance);
        $entityManager->flush();
    }
}
