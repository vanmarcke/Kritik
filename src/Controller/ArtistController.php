<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface as EntityManager;
use App\Entity\Artist;
use App\Form\ArtistType;
use App\Repository\ArtistRepository;
use Symfony\Component\HttpFoundation\Request;

//Pour pouvoir utiliser l'annotation IsGranted
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted; // ou mettre à jour le fichier security.yaml


class ArtistController extends AbstractController
{
    /**
     * @Route("/artist", name="artist")
     * @isGranted("ROLE_ADMIN")
     */
    public function index(ArtistRepository $ar)
    {        
        $liste_artistes = $ar->findAll();
        // dd($liste_artistes);
        return $this->render('artist/index.html.twig', [
            'liste_artistes' => $liste_artistes,
        ]);
    }

    /**
     * @Route("/artist/nouveau", name="artist_nouveau")
     */
    public function nouveau(EntityManager $em, Request $rq) {                
        // La classe Request va permettre de récupérer les informations contenues dans les superglobales ($GET,  $POST, ...)

        if($rq->isMethod("POST")){
            // L'objet $rq a une proprété request qui est un objet qui permet de récupérer le $_POST
            // L'objet $rq a une proprété query qui est un objet qui permet de récupérer le $_GET
            $nom = $rq->request->get("name");
            $desc = $rq->request->get("description");
            //dd($non, $desc);
            // EXO : terminer la méthode pour qu'un nouvel artiste soit enregistrer en BDD. Ensuite on affiche la liste des artistes
            $artiste = new Artist;
            $artiste->setName($nom);
            $artiste->setDescription($desc);
            // la méthode "persist" de l'objet $em permet l'insertion ou la modification en BDD
            $em->persist($artiste);
            // pour exécuter les requêtes insertion ou modification en attente, il faut exécuter la méthode
            // "flush" de l'objet $em
            $em->flush();
            $this->addflash("success", "L'artiste a bien été créé");

            // redirection vers la route "artist"
            return $this->redirectToRoute("artist");      
       
        }
        
        return $this->render("artist/formulaire.html.twig");

        /*
        $artiste = new Artist;
        $artiste->setName("Jimi Hendrix");
        $artiste->setDescription("Guitariste de légende !");
        // la méthode "persist" de l'objet $em permet l'insertion ou la modification en BDD
        $em->persist($artiste);
        // pour exécuter les requêtes insertion ou modification en attente, il faut exécuter la méthode
        // "flush" de l'objet $em
        $em->flush();

        // redirection vers la route "artist"
        return $this->redirectToRoute("artist");
        */
    }

    
        /**
         * @Route("/artist/new", name="artist_new")
         */

        public function new(Request $rq, EntityManager $em){
            $nvArtiste = new Artist;
            $formArtiste = $this->createForm(ArtistType::class, $nvArtiste);
            $formArtiste->handleRequest($rq);
            if($formArtiste->isSubmitted() && $formArtiste->isValid()){
                $em->persist($nvArtiste);
                $em->flush();
                $this->addflash("success", "L'artiste a bien été créé");
                return $this->redirectToRoute("artist");
            }
            return $this->render("artist/form.html.twig", [ 
                "form" => $formArtiste->createView(), 
                "bouton" => "Enregistrer" ]);
         }       
       
    
    // créer une route "artist/ajouter/beyonce => ça ajoute dans la 
    // BDD un artiste beyonce, et ensuite redirection vers la route "artiste"

    /**
     * @Route("/artist/ajouter/{nom}/{description}", name="artist_ajouter_name_description")
     */
    public function ajouter(EntityManager $em, $nom, $description) {
        $artiste = new Artist;
        $artiste->setName($nom);
        $artiste->setDescription($description);
        // la méthode "persist" de l'objet $em permet l'insertion ou la modification en BDD
        $em->persist($artiste);
        // pour exécuter les requêtes insertion ou modification en attente, il faut exécuter la méthode
        // "flush" de l'objet $em
        $em->flush();
        $this->addflash("success", "L'artiste a bien été créé");

        // redirection vers la route "artist"
        return $this->redirectToRoute("artist");
    }

    /**
     * @Route("/fiche/artist/{id}", name="artist_fiche", requirements={"id"="\d+"})
     * à cause de requirements, id doit etre composé d'1 ou plusieurs chiffres
     */
        public function fiche(ArtistRepository $ar, $id)
        {
            $artiste = $ar->find($id);
            if(!empty($artiste)){
                return $this->render("artist/fiche.html.twig", [ "artiste" => $artiste ] );
            }
            return $this->redirectToRoute("artist");
            
        }

    /**
     * @Route("/artist/modifier/{id}", name="artist_modifier", requirements={"id"="\d+"})
     * 
     */
        public function modifier(Request $rq, EntityManager $em, ArtistRepository $ar, $id)
        {
            $artisteAmodifier = $ar->find($id);
            $formArtiste = $this->createForm(ArtistType::class, $artisteAmodifier);
            $formArtiste->handleRequest($rq);
            if($formArtiste->isSubmitted() && $formArtiste->isValid()){
                // $em->persist($artisteAmodifier);  persist n'est pas obligatoire !!!
                $em->flush();
                $this->addflash("success", "L'artiste a bien été modifié");
                return $this->redirectToRoute("artist");
            }
            return $this->render("artist/form.html.twig", [ 
                "form" => $formArtiste->createView(), 
                "bouton" => "Modifier",
                "titre" => "Modification de l'artiste n°$id"
                 ]);          
        }

    /**
     * @Route("/artist/supprimer/{id}", name="artist_supprimer", requirements={"id"="\d+"})
     * 
     */
        public function supprimer(Request $rq, EntityManager $em, ArtistRepository $ar, $id)
        {
            $artisteAsupprimer = $ar->find($id);
            $formArtiste = $this->createForm(ArtistType::class, $artisteAsupprimer);
            $formArtiste->handleRequest($rq);
            if($formArtiste->isSubmitted() && $formArtiste->isValid()){   
            $em->remove($artisteAsupprimer);
            $em->flush();
            $this->addflash("success", "L'artiste a bien été supprimé");
            return $this->redirectToRoute("artist");
        }
        $this->addflash("warning", "<strong>Confirmation</strong> de suppression");
        return $this->render("artist/form.html.twig", [ 
            "form" => $formArtiste->createView(), 
            "bouton" => "Confirmer",
            "titre" => "Suppression de l'artsite n°$id"
             ]);
    }
                
    

}