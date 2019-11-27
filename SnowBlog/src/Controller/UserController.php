<?php

namespace App\Controller;

use App\Repository\FigureRepository;
use App\Repository\CommentRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * @Route("/admin/profil", name="profil")
     */
    public function index(CommentRepository $repoComment, FigureRepository $repoFigure)
    {
        $user = $this->getUser();

        $comment = $repoComment->findByUser($user->getId());
        $figure = $repoFigure->findByUser($user->getId());



        return $this->render('user/profil.html.twig', [
            'user' => $user,
            'figure' => $figure,
            'comment' => $comment
        ]);
    }
}
