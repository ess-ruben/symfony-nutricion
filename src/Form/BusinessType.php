<?php

namespace App\Form;

use App\Entity\Core\Business;
use App\Entity\Client\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BusinessType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $entity = $options['data'];
        $builder
            //->add('description')
            //->add('name')
        ;

        $builder
            ->add('address', AddressType::class, [
                'data' => $entity->getAddress() ?? new Address(),
                'label' => false,
                'required' => true,
                'mapped' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Business::class,
        ]);
    }
}
