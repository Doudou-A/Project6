<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Figure;
use App\Repository\FigureRepository;

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
    * @Route("/", name="home")
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
     */
    public function create(Request $request, ObjectManager $manager)
    {
        $figure = new Figure();

        $form = $this->createFormBuilder($figure)
                     ->add('name', TextType::class,  [
                         'attr' => [
                             'placeholder' => "Nom de la Figure"
                         ]
                     ])
                     ->add('content', TextareaType::class, [
                        'attr' => [
                            'placeholder' => "Nom de la Figure"
                        ]
                     ])
                     ->add('image', TextType::class, [
                        'attr' => [
                            'placeholder' => "Nom de la Figure"
                        ]
                     ])
                     ->getForm();

        return $this->render('blog/create.html.twig', [
            'formFigure' =>$form->createView()
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
