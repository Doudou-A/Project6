<?php

namespace App\Controller;

use App\Entity\Forum;
use App\Entity\Media;
use App\Entity\Figure;
use App\Entity\Category;
use App\Form\ForumType;
use App\Form\CategoryType;
use App\Form\FigureType;
use PhpParser\Node\Stmt\Else_;
use App\Repository\UserRepository;
use App\Repository\ForumRepository;
use App\Repository\MediaRepository;
use App\Repository\FigureRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    /**
     * @Route("/category/all", name="categoryAllView")
     */
    public function categoryAllView(CategoryRepository $repo)
    { 
        $category = $repo->findBy(array(), array('name' => 'ASC'));;

        return $this->render('blog/category.html.twig', [
            'categorys' => $category
        ]);
    }

    /**
     * @Route("/category/{id}/show", name="category_show")
     */
    public function categoryShow($id, FigureRepository $repo)
    { 
        $figures = $repo->findByCategory($id);

        return $this->render('blog/categoryShow.html.twig', [
            'figures' => $figures
        ]);
    }

    /**
     * @Route("/admin/supprime/{entity}/{id}", name="delete")
     */
    public function delete($entity, $id, ForumRepository $repoForum, FigureRepository $repoFigure, UserRepository $repoUser, MediaRepository $repoMedia, CategoryRepository $repoCategory, ObjectManager $manager)
    {
        if ($entity == 'user') {
            $user = $repoUser->find($id);
            $manager->remove($user);
        } elseif ($entity == 'figure') {
            $figure = $repoFigure->find($id);
            $manager->remove($figure);
        } elseif ($entity == 'forum') {
            $forum = $repoForum->find($id);
            $manager->remove($forum);
        } elseif ($entity == 'category') {
            $category = $repoCategory->find($id);
            $manager->remove($category);
        } elseif ($entity == 'media') {
            $media = $repoMedia->find($id);
            $mediaRoute = $repoMedia->find($id);
            $manager->remove($media);
        }
        $manager->flush();

        if ($entity == 'figure') {
            return $this->redirectToRoute('blog');
        } elseif ($entity == 'media') {
            return $this->redirectToRoute('blog_show', ['id' => $mediaRoute->getFigure()->getId()]);
        } elseif ($entity == 'category') {
            return $this->redirectToRoute('categoryAllView');
        }
    }

    /**
     * @Route("/", name="blog")
     */
    public function index(FigureRepository $repo)
    { 
        $figures = $repo->findBy(array(), array('id' => 'DESC'), 5);

        return $this->render('index.html.twig', [
            'controller_name' => 'BlogController',
            'figures' => $figures
        ]);
    }

    /**
     * @Route("/load/{id}", name="annonce-ajax-next", options = { "expose" = true } )
     */
    public function viewAction(Request $request, $id, FigureRepository $repo)
    {
        echo 'ok';
        exit;
        //if ($request->isXmlHttpRequest()) {
        $figures = $repo->findOther($id);

        var_dump($figures);

        $response = new JsonResponse();
        return $response->setData($figures);
        // }
    }

     /**
     * @Route("/blog/category/new", name="category_new")
     * @Route("/blog/category/{id}/edit", name="category_edit")
     */
    public function formCategory(Category $category = null, Request $request, ObjectManager $manager)
    {
        if (!$category){
            $category = new Category();
        }

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $figuresCategory = $form['figures']->getData();
            foreach ( $figuresCategory as $figureCategory )
            {
                $figureCategory->setCategory($category);
                $manager->persist($figureCategory);
            }

            $manager->persist($category);
            $manager->flush();

            return $this->redirectToRoute('categoryAllView');
        }

        return $this->render('blog/formCategory.html.twig', [
            'formCategory' => $form->createView(),
        ]);
    }

    /**
     *  @Route("/blog/new", name="blog_create")
     *  @Route("/blog/{id}/edit", name="blog_edit")
     */
    public function formFigure(Figure $figure = null, Media $media = null, Request $request, ObjectManager $manager)
    {
        if (!$figure) {
            $figure = new Figure();
        }
        $user = $this->getUser();

        if (!$media) {
            $media = new Media();
        }

        $originalMedias = new ArrayCollection();

        foreach ($figure->getMedias() as $media) {
            $originalMedias->add($media);
        }

        $form = $this->createForm(FigureType::class, $figure);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$figure->getId()) {
                $figure->setDateCreated(new \DateTime());
            } else {
                $figure->setDateLastUpdate(new \Datetime());
            }
            $figure->setUser($user);
            $imageFile = $form['image']->getData();
            $mediaImages = $form['medias']->getData();

            if ($imageFile) {
                $newFileName = $this->generateUniqueFileName() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('figuresImg_directory'),
                        $newFileName
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $figure->setImage($newFileName);
            }
            
            foreach ($mediaImages as $mediaImage) {
                $media->setFigure($figure);
                $manager->persist($mediaImage);
            }

            $manager->persist($user);
            $manager->persist($figure);
            $manager->flush();

            return $this->redirectToRoute('blog_show', ['id' => $figure->getid()]);
        }

        return $this->render('blog/formFigure.html.twig', [
            'formFigure' => $form->createView(),
            'editMode' => $figure->getId() !== null
        ]);
    }

    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }

    /**
     *  @Route("/blog/{id}", name="blog_show")
     */
    public function show(Figure $figure, MediaRepository $repo, Request $request, ObjectManager $manager)
    {
        $figureForum = new Forum();
        $user = $this->getUser();

        $form = $this->createForm(ForumType::class, $figureForum);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $figureForum->setDateCreated(new \DateTime());
            $figureForum->setUser($user);
            $figureForum->setFigure($figure);

            $manager->persist($user);
            $manager->persist($figure);
            $manager->persist($figureForum);
            $manager->flush();

            return $this->redirectToRoute('blog_show', ['id' => $figure->getId()]);
        }

        $medias = $repo->findBy(array('figure' => $figure->getId()));

        return $this->render('blog/show.html.twig', [
            'figure' => $figure,
            'user' => $user,
            'formForum' => $form->createView(),
            'medias' => $medias,
        ]);
    }
}
