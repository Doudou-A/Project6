<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\FigureRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{

     /**
     * @Route("admin/category/new", name="category_create")
     * @Route("admin/category/{id}/edit", name="category_edit")
     */
    public function formCategory(Category $category = null, Request $request, ObjectManager $manager, FigureRepository $repo)
    {
        if (!$category){
            $category = new Category();
        }

        $figures = $repo->findByCategory(null);

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

        return $this->render('category/categoryForm.html.twig', [
            'categoryForm' => $form->createView(),
            'figures' => $figures
        ]);
    }

    /**
     * @Route("/category/all", name="categoryAllView")
     */
    public function categoryAllView(CategoryRepository $repo)
    { 
        $category = $repo->findBy(array(), array('name' => 'ASC'));;

        return $this->render('category/category.html.twig', [
            'categorys' => $category
        ]);
    }

    /**
     * @Route("/category/{id}/show", name="category_show")
     */
    public function categoryShow($id, FigureRepository $repo)
    { 
        $figures = $repo->findByCategory($id);

        return $this->render('category/categoryShow.html.twig', [
            'figures' => $figures
        ]);
    }
}
