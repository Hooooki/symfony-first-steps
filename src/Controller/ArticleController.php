<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{

    /**
     * @Route("/articles", name="article.index")
     * @param ArticleRepository $articleRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function index(ArticleRepository $articleRepository, PaginatorInterface $paginator, Request $request): Response
    {

        $pagination = $paginator->paginate(
            $articleRepository->findAll(),
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('article/index.html.twig', [

            'pagination' => $pagination

        ]);
    }

    /**
     * @Route("/articles/{slug}", name="article.show")
     * @param string $slug
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    public function show(string $slug ,ArticleRepository $articleRepository) : Response
    {

        $article = $articleRepository->findOneBy(['slug' => $slug]);

        if(!$article) throw $this->createNotFoundException('This article does not exist');

        return $this->render('article/show.html.twig', [

            'article' => $article

        ]);
    }

}
