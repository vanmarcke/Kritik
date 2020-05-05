<?php

namespace App\Controller;

use stdClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ArtistRepository;
use App\Repository\RecordRepository as RecordRepo;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(ArtistRepository $ar, RecordRepo $rr){
        $artistes = $ar->findAll();
        // $artistes = $ar->find9Artists(); //   affiche les 9 1er artistes

        // $albums = $rr->findAll();
        $albums = $rr->find9Recents();   // affiche les 9 albums les plus récents

        // Pour récupérer l'utilisateur actuellement connecté
        // $user = $this->getUser();
        // Si l'utilisateur n'est pas connecté, $thisUser() renvoie null
        return $this->render("accueil/index.html.twig", compact("artistes", "albums"));
        
            
        
    }
        
    /**
     * @Route("/", name="home_test")
     */
    public function index()
    {
        // return $this->json([
        //     'message' => 'Welcome to your new controller!',
        //     'path' => 'src/Controller/HomeController.php',
        // ]);

        $tableau = ["Un", "Deux" ,"Trois", "Quatre"];
        $tableau_associatif = ["nom" => "Cérien", "prenom" => "Jean", "age" => 22];

        $objet = new stdClass;
        $objet->nom ="Mentor";
        $objet->prenom ="Gérard";
        $objet->age ="33";


        // dump($tableau, $tableau_associatif); // var_dump
        // dd($tableau, $tableau_associatif); // equivalant a vardump, le code qui suit n'est pas exécuté
        return $this->render("base.html.twig", [ 
            "variable" => 5,
            "tableau" => $tableau,
            "tableau_associatif" => $tableau_associatif,
            "objet" => $objet
        ]);
    }

    /**
     * @Route("/test", name="test")
     */
    public function test()
    {
        return $this->json([
            'message' => 'methode test !',
            'path' => 'test route',
        ]);
    }

    /**
     * @Route("/livre/{id}", name="afficher_livre")
     */
    public function afficher($id) {
        return $this->json([
            'message' => "l'identifiant est $id",            
        ]);
    }

    // Exo :
    // Créer une route qui va s'appelle "calcul"
    // url : /calcul/{nombre}
    // cette méthode doit retourner ce message :
    // 'message' => "$nombre *2 = "

    /**
     * @Route("/calcul/{nombre}", name="calcul_nombre")
     */
    public function calcul($nombre) {
        return $this->render("accueil/message.html.twig",[
            'nombre' => $nombre,        
        ]);
    }
    
}
