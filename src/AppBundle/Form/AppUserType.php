<?php
/**
 * Created by PhpStorm.
 * User: giebelj
 * Date: 23.05.18
 * Time: 11:03
 */

namespace AppBundle\Form;

use AppBundle\Entity\AppUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class AppUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add('name', TextType::class)
            ->add('surname', TextType::class)
            ->add('birthday', DateType::class)
            ->add('postCode', TextType::class,array(
                'required' => false,
                'empty_data' => ''
            ))
            ->add('city', TextType::class,array(
                'required' => false,
                'empty_data' => ''
            ))
            ->add('street',TextType::class,array(
                'required' => false,
                'empty_data' => ''
            ))
            ->add('email',EmailType::class)->addEventListener(FormEvents::PRE_SET_DATA,function (FormEvent $event){
                $data = $event->getData();
                $form  = $event->getForm();


                if(!$data || null === $data->getEmail()){
                    $form
                        ->add('pictureUrl', FileType::class, array('label' => 'Upload a profile picture'))
                        ->add('plainPassword', RepeatedType::class, array(
                        'type' => PasswordType::class,
                        'first_options'  => array('label' => 'Password'),
                        'second_options' => array('label' => 'Repeat Password'),
                    ));
                }
            });

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => AppUser::class,
        ));
    }

}