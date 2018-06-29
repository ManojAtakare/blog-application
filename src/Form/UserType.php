<?php
/**
 * Created by PhpStorm.
 * User: manoj
 * Date: 27/6/18
 * Time: 5:02 PM
 */


namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('full_name', TextType::class,array('attr'=>array('class'=>'form-control','placeholder'=>'Fullname')))
            ->add('mobile_number', TextType::class,array('attr'=>array('class'=>'form-control','placeholder'=>'Mobile Number')))
            ->add('username', TextType::class,array('attr'=>array('class'=>'form-control','placeholder'=>'Username')))
            ->add('password', PasswordType::class,array('attr'=>array('class'=>'form-control','placeholder'=>'Password')))
            ->add('Sign Up', SubmitType::class,array('attr'=>array('label'=>'Sign Up','class'=>'btn btn-primary mt-3','style'=>'margin-top:10px;','placeholder'=>'Password')))
            ->getForm()
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Users::class,
        ));
    }
}