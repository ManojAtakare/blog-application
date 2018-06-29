<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\Users;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use App\Form\UserType;

class SecurityController extends Controller
{


    /**
     * @Route("/admin")
     */

    public function admin()
    {
        error_log(".admin.");
        return new Response('<html><body>Admin page!</body></html>');
    }


    public function logged(EntityManagerInterface $em, Request $request, AuthenticationUtils $authUtils)
    {

        error_log(".OMG.");

        return $this->render('security/logged.html.twig', array(
            'username' => $_POST['_username'],
            'password' => $_POST['_password'],
        ));
    }

    public function login(Request $request, AuthenticationUtils $authenticationUtils)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();


        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }


    public function signup(AuthenticationUtils $authenticationUtils){

//        $formFactory = Forms::createFormFactory();

        $error = $authenticationUtils->getLastAuthenticationError();

        $newUsers =  new Users();
        $form = $this->createFormBuilder($newUsers)
            ->add('full_name', TextType::class,array('attr'=>array('class'=>'form-control','placeholder'=>'Fullname')))
            ->add('mobile_number', TextType::class,array('attr'=>array('class'=>'form-control','placeholder'=>'Mobile Number')))
            ->add('username', TextType::class,array('attr'=>array('class'=>'form-control','placeholder'=>'Username')))
            ->add('password', PasswordType::class,array('attr'=>array('class'=>'form-control','placeholder'=>'Password')))
            ->add('Sign Up', SubmitType::class,array('attr'=>array('label'=>'Sign Up','class'=>'btn btn-primary mt-3','style'=>'margin-top:10px;')))
            ->getForm();
        return $this->render('security/signup.html.twig',array('form'=> $form->createView(),'error'=> $error));
       // return $this->render('security/signup.html.twig');
    }

    public function sign_up_user(EntityManagerInterface $em, Request $request, AuthenticationUtils $authUtils, UserPasswordEncoderInterface $passwordEncoder){

        $content = $request->request->all();

        $content = $content['form'];


        $entityManager = $this->getDoctrine()->getManager();

        $newUsers =  new Users();




        $form = $this->createForm(UserType::class, $newUsers);

        $errors = array();

        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }

        $form->handleRequest($request);
        if (empty($errors)) {
            $newUsers->setFullName($content['full_name']);
            $newUsers->setMobileNumber($content['mobile_number']);
            $newUsers->setUsername($content['username']);
            $password = $passwordEncoder->encodePassword($newUsers, $newUsers->getPassword());
            $newUsers->setPassword($password);

            #$newUsers->setPassword($content['password']);
            $entityManager->persist($newUsers);
            $entityManager->flush();


            return $this->render('security/login.html.twig', array(
                'last_username' => $content['full_name'],
                'error'         => '',
            ));
        }else{

            $newUsers =  new Users();
            $form = $this->createFormBuilder($newUsers)
                ->add('full_name', TextType::class,array('attr'=>array('class'=>'form-control','placeholder'=>'Fullname')))
                ->add('mobile_number', TextType::class,array('attr'=>array('class'=>'form-control','placeholder'=>'Mobile Number')))
                ->add('username', TextType::class,array('attr'=>array('class'=>'form-control','placeholder'=>'Username')))
                ->add('password', PasswordType::class,array('attr'=>array('class'=>'form-control','placeholder'=>'Password')))
                ->add('Sign Up', SubmitType::class,array('attr'=>array('label'=>'Sign Up','class'=>'btn btn-primary mt-3','style'=>'margin-top:10px;','placeholder'=>'Password')))
                ->getForm();
            return $this->render('security/signup.html.twig',array('form'=> $form->createView(),'error'=> $errors));
        }




    }







}
