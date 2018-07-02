<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Task;
use App\Entity\BlogPost;
use App\Entity\BlogComment;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class BlogController extends Controller
{




    /**
     * @Route("/blog", name="blog")
     */
    public function index(Request $request)
    {
        $task = new Task();
        $task->setTask('Write a blog post');
        $task->setDueDate(new \DateTime('tomorrow'));

        $form = $this->createFormBuilder($task)
            ->add('task', TextType::class)
            ->add('dueDate', DateType::class)
            ->add('save', SubmitType::class, array('label' => 'Create Task'))
            ->getForm();

        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'form' => $form->createView(),
        ]);
    }


    public function blog(){

        $questions = $this->getDoctrine()
            ->getRepository('App\Entity\BlogPost')->findAll();


        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'blogs'=>$questions
        ]);
    }

    public function newblog(Request $request){

        $newblogs = new BlogPost();
        $form = $this->createFormBuilder($newblogs)
            ->add('title', TextType::class,array('attr'=>array('class'=>'form-control','placeholder'=>'Title')))
            ->add('content', TextareaType::class,array('attr'=>array('class'=>'form-control','placeholder'=>'Content')))
            ->add('Create', SubmitType::class,array('attr'=>array('label'=>'Sign Up','class'=>'btn btn-primary mt-3','style'=>'margin-top:10px;')))
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $blog = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();



            $userInfo =  $this->getUser();
            $newblogs->setCreatedAt(new \DateTime());
            $newblogs->setAuthor($userInfo->getFullname());
            $newblogs->setStatus(0);
            $entityManager->persist($blog);
            $entityManager->flush();



            return $this->redirect($this->generateUrl('blog'));

        }


        return $this->render('blog/create.html.twig', [
            'controller_name' => 'BlogController',
            'form'=> $form->createView(),
            'error'=> ''
        ]);
    }


    /**
     * @Route("/blogs/comment/{id}", name="blogcomment")
     */


    public function blogcomment(Request $request, $id)
    {
        $newcomment = new BlogComment();
        $form = $this->createFormBuilder($newcomment)
            ->add('visitor_name', TextType::class,array('attr'=>array('class'=>'form-control','placeholder'=>'Visitor Name')))
            ->add('Comment', TextareaType::class,array('attr'=>array('class'=>'form-control','placeholder'=>'Comment')))
            ->add('Save', SubmitType::class,array('attr'=>array('label'=>'Comment','class'=>'btn btn-primary mt-3','style'=>'margin-top:10px;')))
            ->getForm();


        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $blog = $form->getData();



            $entityManager = $this->getDoctrine()->getManager();




            $newcomment->setPOSTId($id);
            $newcomment->setCreatedAt(new \DateTime());


            $entityManager->persist($blog);
            $entityManager->flush();



            return $this->redirect($this->generateUrl('blog'));

        }

        $comment = $this->getDoctrine()
            ->getRepository('App\Entity\BlogComment')->findBy(['postId' => $id]);


        $blogs = $this->getDoctrine()
            ->getRepository('App\Entity\BlogPost')->find($id);


        return $this->render('blog/comment.html.twig', [
            'controller_name' => 'BlogController',
            'comments'=>$comment,
            'id'=>$id,
            'blogs'=>$blogs,
            'form'=> $form->createView(),
            'error'=> ''
        ]);

    }


    /**
     * @Route("/blogs/approve/{id}", name="blogapprove")
     */

    public function blogapprove($id){

        $entityManager = $this->getDoctrine()->getManager();
        $blog = $entityManager->getRepository(BlogPost::class)->find($id);

        if (!$blog) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $blog->setStatus(1);
        $entityManager->flush();

        return $this->redirectToRoute('blog');
    }

    public function blogcommentsave(Request $request)
    {
      print_r($request);
      exit;




        $newblogs = new BlogPost();
        $form = $this->createFormBuilder($newblogs)
            ->add('title', TextType::class,array('attr'=>array('class'=>'form-control','placeholder'=>'Title')))
            ->add('content', TextareaType::class,array('attr'=>array('class'=>'form-control','placeholder'=>'Content')))
            ->add('Create', SubmitType::class,array('attr'=>array('label'=>'Sign Up','class'=>'btn btn-primary mt-3','style'=>'margin-top:10px;')))
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $blog = $form->getData();



            $entityManager = $this->getDoctrine()->getManager();



            $newblogs->setCreatedAt(new \DateTime());
            $entityManager->persist($blog);
            $entityManager->flush();



            return $this->redirect($this->generateUrl('blog'));

        }


        return $this->render('blog/create.html.twig', [
            'controller_name' => 'BlogController',
            'form'=> $form->createView(),
            'error'=> ''
        ]);
    }




}
