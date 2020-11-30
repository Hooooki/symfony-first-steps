<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;

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
            6
        );

        return $this->render('article/index.html.twig', [

            'pagination' => $pagination

        ]);
    }

    /**
     * @Route("/articles/{id}/edit", name="article.edit")
     * @param Article $article
     * @param ArticleRepository $articleRepository
     * @param Request $request
     * @return Response
     */
    public function update(Article $article, ArticleRepository $articleRepository, Request $request) : Response {


        $articleForm = $this->createForm(ArticleType::class, $article);

        $articleForm->handleRequest($request);

        if ($articleForm->isSubmitted())
        {

            $manager = $this->getDoctrine()->getManager();

            $slug = (new Slugify())->slugify($article->getTitle());

            $article->setSlug($slug);

            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('article.show', [
                'slug' => $article->getSlug()
            ]);

        }

        return $this->render('article/update.html.twig', [

            'articleForm' => $articleForm->createView()

        ]);

    }

    /**
     * @Route("/articles/{id}/delete", name="article.delete")
     * @param Article $article
     * @param Request $request
     * @return Response
     */
    public function delete(Article $article, Request $request) : Response {

        if(!$article) throw $this->createNotFoundException('This article does not exist');

        $csrfToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('delete-item', $csrfToken))
        {
            $manager = $this->getDoctrine()->getManager();

            $manager->remove($article);

            $manager->flush();

            $this->addFlash('success', "L'article {$article->getTitle()} a bien été supprimé !");

        }
        else
        {
            throw new InvalidCsrfTokenException("CSRF Token error");
        }

        return $this->redirectToRoute('article.index');

    }

    /**
     * @Route("/articles/new", name="article.create")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request) : Response {

        $article = new Article();

        $articleForm = $this->createForm(ArticleType::class, $article);

        $articleForm->handleRequest($request);

        if ($articleForm->isSubmitted() && $articleForm->isValid())
        {

            $manager = $this->getDoctrine()->getManager();

            $article->setCreatedAt(new \DateTime);

            $slug = (new Slugify())->slugify($article->getTitle());

            $article->setSlug($slug);

            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('article.show', [
                'slug' => $article->getSlug()
            ]);

        }

        return $this->render('article/create.html.twig', [

            'articleForm' => $articleForm->createView()

        ]);

    }

    /**
     * @Route("/articles/{id}/like", name="article.like")
     * @param Article $article
     * @param Request $request
     * @return Response
     */
    public function like(Article $article, Request $request) : Response {

        if (!$article)
        {
            return $this->json('The article does not exist', 404);
        }

        $manager = $this->getDoctrine()->getManager();

        $article->incrementLikes();

        $manager->persist($article);
        $manager->flush();

        return $this->json(['likes' => $article->getLikes()], 200);

    }

    /**
     * @Route("/articles/{id}/unlike", name="article.unlike")
     * @param Article $article
     * @param Request $request
     * @return Response
     */
    public function unlike(Article $article, Request $request) : Response {

        if (!$article)
        {
            return $this->json('The article does not exist', 404);
        }

        $manager = $this->getDoctrine()->getManager();

        $article->decrementLikes();

        $manager->persist($article);
        $manager->flush();

        return $this->json(['likes' => $article->getLikes()], 200);

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
