<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Figure;
use Sdz\BlogBundle\Entity\Article;
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
    public function create( Request $request, ObjectManager $manager)
    {
        $figure = new Figure();

        $form = $this->createFormBuilder($figure)
                     ->add('name')
                     ->add('content')
                     ->add('summary')
                     ->add('image')
                     ->getForm();
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $figure->setDateCreated(new \DateTime());

            $manager->persist($figure);
            $manager->flush();

            return $this->redirectToRoute('blog_show', ['id' =>$figure->getid()]);
        } 

        return $this->render('blog/create.html.twig', [
            'formFigure' =>$form->createView()
        ]);
    }

    /** 
    * @Route("/blog/admin", name="login")
    */
    public function login(Request $request, AuthenticationUtils $authUtils)
    {
        $error = $authUtils->getLastAuthenticationError();

        $lastUsername = $authUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' =>$lastUsername,
            'error'         =>$error
        ]);
    }

    /**
     *  @Route("/blog/admin/{id}", name="update")
     */
    public function updateFigure($id, FigureRepository $repo, ObjectManager $manager, Request $request)
    {
        $figures = $repo->find($id);

        if (!$figures) {
            throw $this->createNotFoundException(
                'Aucun article trouvé pour cet id : '.$id
            );
        }

        $figure = new Figure();
        $figure->setName($figures->getName());
        $figure->setContent($figures->getContent());
        $figure->setSummary($figures->getSummary());
        $figure->setImage($figures->getImage());


        $form = $this->createFormBuilder($figure)
                     ->add('name')
                     ->add('content')
                     ->add('summary')
                     ->add('image')
                     ->getForm();
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $figure->setDateUpdate(new \DateTime());
            $figures->setName($figure->getName());
            $figures->setContent($figure->getContent());
            $figures->setSummary($figure->getSummary());
            $figures->setImage($figure->getImage());

            $manager->flush();

            return $this->redirectToRoute('blog_show', ['id' =>$figure->getid()]);
        } 

        return $this->render('blog/admin/updateFigure.html.twig', [
            'figure' => $figure,
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
