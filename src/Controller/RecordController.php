<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\RecordRepository as RecordRepo;
use App\Repository\RankingRepository as RankingRepo;
use Doctrine\ORM\EntityManagerInterface as EntityManager;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Record;
use App\Form\RecordType;

class RecordController extends AbstractController
{
    /**
     * @Route("/record", name="record")
     */
    public function index(RecordRepo $rr)
    {
        return $this->render('record/index.html.twig', [
            "liste_records" => $rr->findAll()
        ]);
    }

    /**
     * @Route("/record/new", name="record_new")
     */
    public function new(Request $rq, EntityManager $em)
    {
        $nvRecord = new Record;
        $formRecord = $this->createForm(RecordType::class, $nvRecord);
        $formRecord->handleRequest($rq);
        if($formRecord->isSubmitted() && $formRecord->isValid()){
            $image = $formRecord->get("cover")->getData();
            if($image){ // si une image a été téléchargé...
            // dd($image);
                // je récupère le nom du fichier télécharger
                $nomImage = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);

                // je remplace les espaces pade des _
                $nomImage = str_replace(" ", "_", $nomImage);

                // je rajoute un string unique pour eviter d'avoir des doublons et je rajoute l'extension du fichier
                $nomImage .= "_" . uniqid() . "." . $image->guessExtension();

                // j'enregistre l'image télécharger sur mon server, dans le dossier public/img
                $image->move($this->getParameter("dossier_images"), $nomImage);

                // je définie la propriété cover de mon objet Record
                $nvRecord->setCover($nomImage);
            }
            $em->persist($nvRecord);
            $em->flush();
            $this->addflash("success", "L'album a bien été créé");
            return $this->redirectToRoute("record");
        }
        return $this->render('record/form.html.twig', [
            'formRecord' => $formRecord->createView()
        ]);
    }

    /**
     * @Route("/record/modifier/{id}", name="record_modifier", requirements={"id"="\d+"})
     * 
     */
    public function modifier(Request $rq, EntityManager $em, RecordRepo $rr, $id)
    {
        $recordAmodifier = $rr->find($id);
        $formRecord = $this->createForm(RecordType::class, $recordAmodifier);
        $formRecord->handleRequest($rq);
        if($formRecord->isSubmitted() && $formRecord->isValid()){
            $image = $formRecord->get("cover")->getData();
            if($image){ // si une image a été téléchargé...
            // dd($image);
                // je récupère le nom du fichier télécharger
                $nomImage = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);

                // je remplace les espaces pade des _
                $nomImage = str_replace(" ", "_", $nomImage);

                // je rajoute un string unique pour eviter d'avoir des doublons et je rajoute l'extension du fichier
                $nomImage .= "_" . uniqid() . "." . $image->guessExtension();

                // j'enregistre l'image télécharger sur mon server, dans le dossier public/img
                $image->move($this->getParameter("dossier_images"), $nomImage);

                // je définie la propriété cover de mon objet Record
                $recordAmodifier->setCover($nomImage);
            }         
            $em->flush();
            $this->addflash("success", "L'album a bien été modifié");
            return $this->redirectToRoute("record");
        }
        return $this->render("record/form.html.twig", [ 
            "formRecord" => $formRecord->createView(), 
            "bouton" => "Modifier",
            "titre" => "Modification de l'album n°$id",
            "album" => $recordAmodifier
             ]);          
        }

        /**
         * @Route("/record/supprimer/{id}", name="record_supprimer", requirements={"id"="\d+"})
         * 
         */
        public function supprimer(Request $rq, EntityManager $em, RecordRepo $rr, $id)
        {
            $recordAsupprimer = $rr->find($id);
            $formRecord = $this->createForm(RecordType::class, $recordAsupprimer);
            $formRecord->handleRequest($rq);
            if($formRecord->isSubmitted() && $formRecord->isValid()){   
            $em->remove($recordAsupprimer);
            $em->flush();
            $this->addflash("success", "L'album a bien été supprimé");
            return $this->redirectToRoute("record");
        }
        $this->addflash("warning", "<strong>Confirmation</strong> de suppression");
        return $this->render("record/form.html.twig", [ 
            "formRecord" => $formRecord->createView(), 
            "bouton" => "Confirmer",
            "titre" => "Suppression du Record n°$id"
            ]);
    }
        /**
         * @Route("/fiche/record/{id}", name="record_fiche", requirements={"id"="\d+"})
         * à cause de requirements, id doit etre composé d'1 ou plusieurs chiffres
         */
        public function fiche(RecordRepo $rr, RankingRepo $rankr, $id)
        {
            $record = $rr->find($id);
            $noteMoyenne = $rankr->moyenne($id);

            if(!empty($record)){
                return $this->render("record/fiche.html.twig", [ "album" => $record, "noteMoyenne" => $noteMoyenne ] );
            }
            return $this->redirectToRoute("record");
        
    }   

}
