<?php

namespace App\Form;

use App\Entity\Core\User;
use App\Entity\Core\Business;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['data'];

        $builder
            ->add('name')
            ->add('surnames')
            ->add('email', EmailType::class)
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options'  => ['label' => 'Contraseña'],
                'second_options' => ['label' => 'Repite Contraseña'],
                'mapped' => true,
            ])
        ;
        
        if ($user->isBossBusiness()) {
            $builder
            ->add('yourBusiness', BusinessType::class, [
                'data' => $user->getYourBusiness() ?? new Business(),
                'label' => false,
                'required' => true,
                'mapped' => true
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
