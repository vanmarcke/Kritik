<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
// use App\Repository\UserRepository;

class ProfilController extends AbstractController
{
    /**
     * @Route("/profil", name="profil")
     */
    public function index()
    {
        // Pour récupérer les informations de l'utilisateur actuellement connecté,
        // le controller posséde la méthode getUser()
        // $user = $this->getUser();        
        return $this->render('profil/index.html.twig');
        
    }
}


// 1
// Vous allez créer une route "profil" pour afficher la page de profil de l'utilisateur
// dans un controleur ProfilController. Cette route n'est accessible que si l'on est connecté

// Sur cette page, vous affichez les informations de l'utilisateur

// 2
// Vous créer le controller UserController, avec toutes les routes pour le CRUD
// (pour la gestion des user par l'admin)

// 3
// 3) Créer une entité Ranking
// 	   record_id FK
//     user_id  FK
//     note   int  not null 
//     comment text
    
// Rappel : les clés étrangères (FK) correspondent à des relations entre les entités


// Donner la possibilité aux utilisateurs de noter les Record :
// Une note allant de 0 à 10
// La note peut s'accompagner d'un commentaire
// Un User ne doit pas pouvoir mettre plusieurs notes au même Record

// Soit vous créez un formulaire pour choisir le record et mettre une note
// Soit vous mettez un lien sur la fiche du record pour pouvoir le noter
