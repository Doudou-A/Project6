<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Form\FigureType;
use App\Entity\FigureForum;
use App\Form\FigureForumType;
use App\Repository\UserRepository;
use App\Repository\FigureRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    /** 
    * @Route("/admin/dashboard", name="dashboard") 
    */
    public function dashboard(UserRepository $repo)
    {
        $users = $repo->findAll();

        return $this->render('admin/dashboard.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/blog", name="blog")
     */
    public function index(FigureRepository $repo)
    {
        $figures = $repo->findAll();

        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'figures' => $figures
        ]);
    }

    /**
    * @Route("/", name="homepage")
    */
    public function home()
    {
    	return $this->render('blog/home.html.twig', [
            'title' => "Meilleur SnowBlog",
            'age' => 18
        ]);
    }

    /**
     *  @Route("/blog/new", name="blog_create")
     *  @Route("/blog/{id}/edit", name="blog_edit")
     */
    public function formFigure(Figure $figure = null, Request $request, ObjectManager $manager)
    {
        if(!$figure){
            $figure = new Figure();
        }

        $form = $this->createForm(FigureType::class, $figure);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            if(!$figure->getId()){
                $figure->setDateCreated(new \DateTime());
            }
            else{
                $figure->setDateLastUpdate(new \Datetime());
            }

            $manager->persist($figure);
            $manager->flush();

            return $this->redirectToRoute('blog_show', ['id' =>$figure->getid()]);
        } 

        return $this->render('blog/create.html.twig', [
            'formFigure' =>$form->createView(),
            'editMode' => $figure->getId() !==null
        ]);
    }

    /**
     *  @Route("/blog/{id}", name="blog_show")
     */
    public function show(Figure $figure, Request $request, ObjectManager $manager)
    {
        $figureForum = new FigureForum();

        $form = $this->createForm(FigureForumType::class, $figureForum);      

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $figureForum->setDateCreated(new \DateTime())
                        ->setFigure($figure);

            $manager->persist($figureForum);
            $manager->flush();

            return $this->redirectToRoute('blog_show', ['id' => $figure->getId()]);
        }

        return $this->render('blog/show.html.twig', [
            'figure' => $figure,
            'formForum' => $form->createView()
        ]);
    }
}
