<?php

namespace App\Util\Admin\Field;

use App\Form\AddressType;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;

final class AddressField implements FieldInterface
{
    use FieldTrait;

    public static function new(string $propertyName, ?string $label = 'Address'): self
    {
        return (new self())
            ->setProperty($propertyName)
            ->setLabel($label)
            // this template is used in 'index' and 'detail' pages
            ->setTemplatePath('admin/field/address.html.twig')
            // this is used in 'edit' and 'new' pages to edit the field contents
            // you can use your own form types too
            ->setFormType(AddressType::class)
        ;
    }
}