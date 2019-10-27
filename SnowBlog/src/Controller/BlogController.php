<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Form\FigureType;
use App\Entity\Forum;
use App\Form\ForumType;
use App\Repository\UserRepository;
use App\Repository\FigureRepository;
use App\Repository\ForumRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use PhpParser\Node\Stmt\Else_;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{

    /** 
    * @Route("/admin/dashboard", name="dashboard") 
    */
    public function dashboard(UserRepository $repoUser, FigureRepository $repoFigure, ForumRepository $repoForum)
    {
        $figures = $repoFigure->findAll();
        $forums = $repoForum->findBy(array(), array('figure' => 'ASC'));
        $users = $repoUser->findBy(array(), array('email' => 'ASC'));

        return $this->render('admin/dashboard.html.twig', [
            'figures' => $figures,
            'forums' => $forums,
            'users' => $users
        ]);
    }

    /**
     * @Route("/admin/supprime/{entity}/{id}", name="delete")
     */
    public function delete($entity, $id, ForumRepository $repoForum, FigureRepository $repoFigure, UserRepository $repoUser, ObjectManager $manager)
    {
        if ( $entity == 'user') {
            $user = $repoUser->find($id);
            $manager->remove($user);
        } elseif ( $entity == 'figure' ){
            $figure = $repoFigure->find($id);
            $manager->remove($figure);
        } elseif ( $entity == 'forum'){
            $forum = $repoForum->find($id);
            $manager->remove($forum);
        }
        $manager->flush();

        return $this->redirectToRoute('dashboard');

    }

    /** 
    * @Route("/admin/profil", name="profil") 
    */
    public function profil(UserRepository $repoUser, ForumRepository $repoForum, FigureRepository $repoFigure)
    {
        $id = $this->getUser()->getId();
        $user = $this->getUser();
        $figure = $repoFigure->findByUser($id);
        $forum = $repoForum->findByUser($id);

        return $this->render('admin/profil.html.twig', [
            'user' => $user,
            'figure' => $figure,
            'forum' => $forum
        ]);
    }

    /**
     * @Route("/admin/profil/forum", name="profilForum")
     */
    public function profilForum(ForumRepository $repo)
    {
        $id = $this->getUser()->getId();
        $forums = $repo->findByUser($id);

        return $this->render('admin/profil/forum.html.twig', [
            'forums' => $forums
        ]);

    }

    /**
     * @Route("/admin/profil/figure", name="profilFigure")
     */
    public function profilFigure(FigureRepository $repo)
    {
        $id = $this->getUser()->getId();
        $figures = $repo->findByUser($id);

        return $this->render('admin/profil/figure.html.twig', [
            'figures' => $figures
        ]);

    }

    /**
    * @Route("/", name="blog")
    */
    public function index(FigureRepository $repo)
    {
    	$figures = $repo->findAll();

        return $this->render('index.html.twig', [
            'controller_name' => 'BlogController',
            'figures' => $figures
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

        $user = $this->getUser();

        $form = $this->createForm(FigureType::class, $figure);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            if(!$figure->getId()){
                $figure->setDateCreated(new \DateTime());
            }
            else{
                $figure->setDateLastUpdate(new \Datetime());
            }
            $figure->setUser($user);

            $manager->persist($user);
            $manager->persist($figure);
            $manager->flush();

            return $this->redirectToRoute('blog_show', ['id' =>$figure->getid()]);
        } 

        return $this->render('blog/formFigure.html.twig', [
            'formFigure' =>$form->createView(),
            'editMode' => $figure->getId() !==null
        ]);
    }

    /**
     *  @Route("/blog/{id}", name="blog_show")
     */
    public function show(Figure $figure, Request $request, ObjectManager $manager)
    {
        $figureForum = new Forum();
        $user = $this->getUser();

        $form = $this->createForm(ForumType::class, $figureForum);      

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $figureForum->setDateCreated(new \DateTime());
            $figureForum->setUser($user);
            $figureForum->setFigure($figure);

            $manager->persist($user);
            $manager->persist($figure);
            $manager->persist($figureForum);
            $manager->flush();

            return $this->redirectToRoute('blog_show', ['id' => $figure->getId()]);
        }

        return $this->render('blog/show.html.twig', [
            'figure' => $figure,
            'user' => $user,
            'formForum' => $form->createView()
        ]);
    }
}
