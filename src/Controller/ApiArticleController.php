<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ArticleRepository;
use App\Entity\Article;
use App\Service\Utils;
use Doctrine\ORM\EntityManagerInterface;

class ApiArticleController extends AbstractController
{
    #[Route('/api/article/all', name: 'app_api_article_all',methods:'GET')]
    public function getArticle(ArticleRepository $repo ): Response{
        $article = $repo->findAll();
        if (empty($article)) {
            return $this->json(['Erreur'=> 'Le article ne existe pas'],206, [],[]);
        }
        return $this->json($article, 200, ['Content-Type'=>'application/json','Access-Control-Allow-Origin'=>
         '*'],['groups'=>'article:readAll']);
      
    }
    #[Route('/api/article/{id}', name: 'app_api_article_id',methods:'GET')]
    public function getArticleByid(ArticleRepository $repo ,$id ): Response{
        $article = $repo->find($id);
        if (empty($article)) {
            return $this->json(['Erreur'=> 'Le article ne existe pas'],206, [],[]);
        }
        return $this->json($article, 200, ['Content-Type'=>'application/json','Access-Control-Allow-Origin'=>
         '*'],['groups'=>'article:readbyId']);
        
    }
}
