<?php

namespace App\Controller;

use App\Entity\Media;
use App\Entity\Figure;
use App\Entity\Comment;
use App\Form\FigureType;
use App\Form\CommentType;
use App\Repository\UserRepository;
use App\Repository\MediaRepository;
use App\Repository\FigureRepository;
use App\Repository\CommentRepository;
use App\Repository\CategoryRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FigureController extends AbstractController
{

    /**
     * @Route("/admin/supprime/{entity}/{id}", name="delete")
     */
    public function delete($entity, $id, FigureRepository $repoFigure, UserRepository $repoUser, MediaRepository $repoMedia, CategoryRepository $repoCategory, CommentRepository $repoComment, ObjectManager $manager)
    {
        if ($entity == 'user') {
            $user = $repoUser->find($id);
            $manager->remove($user);
        } elseif ($entity == 'figure') {
            $figure = $repoFigure->find($id);
            $manager->remove($figure);
        } elseif ($entity == 'category') {
            $category = $repoCategory->find($id);
            $manager->remove($category);
        } elseif ($entity == 'media') {
            $media = $repoMedia->find($id);
            $mediaRoute = $repoMedia->find($id);
            $manager->remove($media);
        } elseif ($entity == 'comment') {
            $comment = $repoComment->find($id);
            $commentRoute = $repoComment->find($id);
            $manager->remove($comment);
        }
        $manager->flush();

        if ($entity == 'figure') {
            return $this->redirectToRoute('home');
        } elseif ($entity == 'media') {
            return $this->redirectToRoute('figure_show', ['id' => $mediaRoute->getFigure()->getId()]);
        } elseif ($entity == 'category') {
            return $this->redirectToRoute('categoryAllView');
        } elseif ($entity == 'comment') {
            return $this->redirectToRoute('figure_show', ['slug' => $commentRoute->getFigure()->getSlug()]);
        }
    }

    /**
     *  @Route("admin/figure/new", name="figure_create")
     *  @Route("admin/figure/{slug}/edit", name="figure_edit")
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

            $this->addFlash('success', 'Figure AJoutée/Modifiée avec succès !');

            return $this->redirectToRoute('figure_show', [
                'slug' => $figure->getSlug(),
                'page' => 1
                ]);
        }

        return $this->render('figure/figureForm.html.twig', [
            'formFigure' => $form->createView(),
            'editMode' => $figure->getId() !== null,
            'newMode' => $figure->getId() == null,
            'user' => $user,
            'figure' => $figure
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
     * @Route("/index", name="home")
     * @Route("/index/{page}", requirements={"page" = "\d+"}, name="front_figures_index")
     */
    public function index(FigureRepository $repo, $page = null)
    {
        if (!$page) {
            $page = 1;
        }

        $nbFiguresParPage = 10;

        /* $figures = $repo->findBy(array(), array('id' => 'DESC'), 5); */
        $figures = $repo->findAllPagineEtTrie($page, $nbFiguresParPage);
        $user = $this->getUser();

        $pagination = array(
            'page' => $page,
            'nbPages' => ceil(count($figures) / $nbFiguresParPage),
            'nomRoute' => 'front_figures_index',
            'paramsRoute' => array()
        );

        return $this->render('index.html.twig', [
            'controller_name' => 'FigureController',
            'user' => $user,
            'figures' => $figures,
            'pagination' => $pagination
        ]);
    }

    /**
     *  @Route("/figure/{slug}/{page}", requirements={"page" = "\d+"}, name="figure_show")
     */
    public function figureShow(Figure $figure, MediaRepository $repo, CommentRepository $repoComment, Request $request, ObjectManager $manager, $page = null)
    {
        if (!$page){
            $page = 1;
        }

        $figureComment = new Comment();
        $user = $this->getUser();

        $form = $this->createForm(CommentType::class, $figureComment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $figureComment->setDateCreated(new \DateTime());
            $figureComment->setUser($user);
            $figureComment->setFigure($figure);

            $manager->persist($user);
            $manager->persist($figure);
            $manager->persist($figureComment);
            $manager->flush();

            return $this->redirectToRoute('figure_show', [
                'slug' => $figure->getSlug()
                ]);
        }

        $nbCommentsParPage = 5;

        $figureId = $figure->getId();
        $comments = $repoComment->findByFigurePagineEtTrie($page, $nbCommentsParPage, $figureId);
        /* $comments = $repoComment->findAllPagineEtTrie($page, $nbCommentsParPage); */
        $user = $this->getUser();

        $pagination = array(
            'page' => $page,
            'nbPages' => ceil(count($comments) / $nbCommentsParPage),
            'nomRoute' => 'figure_show',
            'paramsRoute' => array('slug' => $figure->getSlug())
        );

        $medias = $repo->findBy(array('figure' => $figure->getId()));

        return $this->render('figure/figureShow.html.twig', [
            'figure' => $figure,
            'user' => $user,
            'comments' => $comments,
            'formComment' => $form->createView(),
            'medias' => $medias,
            'pagination' => $pagination
        ]);
    }
}
