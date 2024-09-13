<?php

namespace App\Form;

use App\Entity\Client\Address;
use App\Entity\Place\Country;
use App\Entity\Place\State;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityManagerInterface;

class AddressType extends AbstractType
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $entity = $options['data'] ?? new Address();
        if(empty($entity->getState())){
            $entity->setState(
                $this->getDefaultState()
            );
        }

        $idDoc = $entity->getId() ?? date('YmdHis'); 

        $builder
            ->add('name')
            ->add('surname')
            ->add('email')
            ->add('phone')
            ->add('nif')
            ->add('city')
            ->add('zip')
            ->add('street')
            ->add('number')
            ->add('lat')
            ->add('lon')
            ->add('country', EntityType::class, [
                'class' => Country::class,
                'attr' => [
                    'class' => 'form-control countrySelect',
                    'data-id' => $idDoc,
                ],
                'placeholder' => 'Selecciona un país',
                'required' => true,
            ])
            ->add('state', EntityType::class, [
                'class' => State::class, // Ajusta la clase de la entidad State
                'placeholder' => 'Selecciona un estado',
                'required' => true,
                'attr' => [
                    'class' => "form-control $idDoc",
                ],
                'choice_attr' => function ($object) {
                    return ['data-country' => $object->getCountry() ? $object->getCountry()->getId() : '0'];
                }
            ])
            //->add('state')
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }

    private function getDefaultState()
    {
        return $this->em->getRepository(State::class)->findOneBy(['country' => 1],['name' => 'ASC']);
    }
}
