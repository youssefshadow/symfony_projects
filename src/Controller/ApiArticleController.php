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
use Symfony\Component\Serializer\SerializerInterface;

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
    #[Route('/api/article/add', name:'app_api_article_add', methods:'PUT')]
    public function addArticle(ArticleRepository $repo, Request $request,
    SerializerInterface $serialize, EntityManagerInterface $em):Response{
        //récupérer le contenu de la requête
        $json = $request->getContent();
        //sérializer le json en tableau
        $data = $serialize->decode($json, 'json');
        //instancier un objet article
        $article = new Article();
        //set des valeurs à l'objet
        $article->setTitre($data['titre']);
        $article->setContenu($data['contenu']);
        $article->setDate(new \DateTimeImmutable($data['date']));
        //récupérer un article
        $recup = $repo->findOneBy(['titre'=>$data['titre'], 'date'=>$article->getDate()]);
        if($recup){
            //renvoyer un json
            return $this->json(['erreur'=>'L\'article '.$article->getTitre().' existe déja en BDD'], 206, 
            ['Content-Type'=>'application/json',
            'Access-Control-Allow-Origin'=> 'localhost',
            'Access-Control-Allow-Methods'=> 'GET'],[]);
        }
        //persister les données
        $em->persist($article);
        //enregistrer en BDD
        $em->flush();
        
        //renvoyer un json
        return $this->json(['erreur'=>'L\'article '.$article->getTitre().' a été ajouté en BDD'], 200, 
        ['Content-Type'=>'application/json',
        'Access-Control-Allow-Origin'=> 'localhost',
        'Access-Control-Allow-Methods'=> 'GET'],[]);
    }
         
        
    }

