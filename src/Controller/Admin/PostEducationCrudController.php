<?php

namespace App\Controller\Admin;

use App\Entity\Cms\PostEducation;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use Vich\UploaderBundle\Form\Type\VichImageType;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class PostEducationCrudController extends AbstractCrudController
{
    private $parameters;
    public function __construct(ParameterBagInterface $parameters) {
        $this->parameters = $parameters;
    }

    public static function getEntityFqcn(): string
    {
        return PostEducation::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // the labels used to refer to this entity in titles, buttons, etc.
            ->setEntityLabelInSingular('Post')
            ->setEntityLabelInPlural('Educación nutricional')
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            
            ImageField::new('fileName', "Imagen")->hideOnForm()
              ->setBasePath($this->parameters->get('app.path.media_file')),
            TextField::new('title','titulo'),
            TextEditorField::new('body','Descripción'),
            BooleanField::new('isActive','Activo'),
            Field::new('file', "Imagen")->hideOnIndex()->hideOnDetail()
                                             ->setFormType(VichImageType::class),
        ];
    }
}
