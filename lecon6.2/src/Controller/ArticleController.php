<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Serializer\SerializerInterface;

class ArticleController extends AbstractFOSRestController
{
    /**
     * @Rest\Get("/articles")
     */
    public function getArticles()
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();

        $data = [];
        foreach ($articles as $article) {
            $data[] = [
                'titre' => $article->getTitre(),
                'contenu' => $article->getContenu(),
                'auteur' => $article->getAuteur(),
                'datePublication' => $article->getDatePublication()->format('Y-m-d H:i:s'),
            ];
        }

        return new JsonResponse($data);
    }

    /**
     * @Rest\Get("/articles/{id}")
     */
    public function getArticle($id)
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);

        if (!$article) {
            return new Response('Article not found', Response::HTTP_NOT_FOUND);
        }

        $data = [
            'titre' => $article->getTitre(),
            'contenu' => $article->getContenu(),
            'auteur' => $article->getAuteur(),
            'datePublication' => $article->getDatePublication()->format('Y-m-d H:i:s'),
        ];

        return new JsonResponse($data);
    }

    /**
     * @Rest\Post("/article")
     */
    public function createArticle(Request $request, SerializerInterface $serializer)
    {
        $data = $request->getContent();

        $article = $serializer->deserialize($data, Article::class, 'json');
        $article->setDatePublication(new \DateTime());

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($article);
        $entityManager->flush();

        return $this->json('Article created successfully', Response::HTTP_CREATED);
    }


    /**
     * @Rest\Put("/article")
     */
    public function updateArticle(Request $request, SerializerInterface $serializer)
    {
        $data = $request->getContent();

        $articleData = $serializer->deserialize($data, Article::class, 'json');

        $entityManager = $this->getDoctrine()->getManager();
        $articleRepository = $entityManager->getRepository(Article::class);

        $article = $articleRepository->findOneBy(['titre' => $articleData->getTitre()]);

        if (!$article) {
            // Create a new article
            $article = new Article();
            $article->setTitre($articleData->getTitre());
        }

        $article->setContenu($articleData->getContenu());
        $article->setAuteur($articleData->getAuteur());
        $article->setDatePublication(new \DateTime());

        $entityManager->persist($article);

        $entityManager->flush();

        return $this->json('Article updated successfully', Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/last3article")
     */
    public function getLast3Articles()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $articleRepository = $entityManager->getRepository(Article::class);

        $articles = $articleRepository->findBy([], ['datePublication' => 'DESC'], 3);

        return $this->json($articles, Response::HTTP_OK);
    }

    /**
     * @Rest\Delete("/article/{id}")
     */
    public function deleteArticle($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $articleRepository = $entityManager->getRepository(Article::class);

        $article = $articleRepository->find($id);

        if (!$article) {
            return $this->json('Article not found', Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($article);
        $entityManager->flush();

        return $this->json('Article deleted successfully', Response::HTTP_OK);
    }
}
