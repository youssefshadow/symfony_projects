<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;

//use Symfony\Component\HttpFoundation\Request;

use App\Entity\Categorie;
use App\Form\CategorieType;


class CategorieController extends AbstractController
{
   
    #[Route('/categorie/all', name:'app_categorie_all')]
    public function showAllCategorie(CategorieRepository $categorieRepository):Response{
        //récuperer dans un tableau tous les categories
        $categorie = $categorieRepository->findAll();
        return $this->render('categorie/index.html.twig', [
            'liste'=> $categorie,
        ]);
    }

    #[Route('/categorie/update/{id}', name:'app_categorie_update')]
    public function updateCategorie(int $id, CategorieRepository $categorieRepository, 
        EntityManagerInterface $em, Request $request):Response{
        $msg = "";
        //récupération de l'objet categorie
        $categorie = $categorieRepository->find($id);
        //instance du formulaire
        $form = $this->createForm(CategorieType::class, $categorie);
        //Récupération des datas du formulaire
        $form->handleRequest($request);
        //Vérification du formulaire
        if($form->isSubmitted() AND $form->isValid()){
            //on fait persister les données
            $em->persist($categorie);
            //on synchronise avec la BDD
            $em->flush();
            //gestion du message de confirmation
            $msg = 'La catégorie : '.$categorie->getId().' à été modifié'; 
        }
        //retourner l'interface twig
        return $this->render('categorie/categorieUpdate.html.twig', [
            'form'=> $form->createView(),
            'msg'=> $msg,
        ]);
    }
    #[Route('/categorie/add', name: 'app_categorie_add')]
    public function addCategorie(EntityManagerInterface $em, Request $request,
        CategorieRepository $repo): Response
    {   
        $msg = "";
        //instancier un objet categorie
        $categorie = new Categorie();
        //créer le formulaire
        $form = $this->createForm(CategorieType::class, $categorie);
        //Récupération des datas du formulaire
        $form->handleRequest($request);
        //dd($request);
        //tester si le formulaire est submit
        if($form->isSubmitted() AND $form->isValid()){
            //récupération de l'enregistrement
            $recup = $repo->findOneBy(['nom'=>$categorie->getNom()]);
            //tester si la catégorie existe déja
            if(!$recup){
                //persister les données du formulaire
                $em->persist($categorie);
                //ajouter en BDD
                $em->flush();
                $msg = "L'article ".$categorie->getNom()." a été ajouté en BDD";
            }
            else{
                $msg = "L'article ".$categorie->getNom()." existe déja en BDD";
            }
        }
        return $this->render('categorie/categorieAdd.html.twig', [
            'form' => $form->createView(),
            'msg' => $msg,
        ]);
    }
}

