<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('roles', TextType::class, [
                'label' => 'Vore rôle'
            ])
            ->add('password')
            ->add('username');
    }

    /*   $user->setPassword($password);
        $user->setEmail('user1@email.com')
            ->setUsername('user1')
            ->setRoles(['ROLE_USER'])
            ->setFirstname('user1')
            ->setLastname('user1')
            ->setPhoneNumber(0600000000)
            ->setAddress($address)
           
        ;*/

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
