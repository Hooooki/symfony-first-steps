<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{

    private $repository;

    public function __construct(CategoryRepository $categoryRepository) {

        $this->repository = $categoryRepository;

    }

    /**
     * @Route("/category", name="category.index")
     * @param CategoryRepository $categoryRepository
     * @return Response
     */
    public function index(PaginatorInterface $paginator, Request $request): Response
    {

        $paginatedCategories = $paginedArticles = $paginator->paginate(
            $this->repository->findAll(),
            $request->query->getInt('page', 1),
            6
        );

        return $this->render('category/index.html.twig', [

            'categories' => $paginatedCategories

        ]);
    }

    /**
     * @Route("/category/{id}", name="category.show")
     * @param Category $category
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function show(Category $category, PaginatorInterface $paginator, Request $request) : Response
    {

        $paginatedArticles = $paginator->paginate(
            $category->getArticles(),
            $request->query->getInt('page', 1),
            6
        );

        return $this->render('category/show.html.twig', [

            'category' => $category,
            'articles' => $paginatedArticles

        ]);
    }

}
