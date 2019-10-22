<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Figure;
use App\Repository\FigureRepository;
use App\Form\FigureType;

class BlogController extends AbstractController
{
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
    public function show(Figure $figure)
    {
        return $this->render('blog/show.html.twig', [
            'figure' => $figure
        ]);
    }
}
