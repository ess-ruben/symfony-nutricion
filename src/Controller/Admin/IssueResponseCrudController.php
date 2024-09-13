<?php

namespace App\Controller\Admin;

use App\Entity\Client\IssueResponse;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class IssueResponseCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return IssueResponse::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
