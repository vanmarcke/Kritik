<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\RecordRepository as RecordRepo;
use App\Form\NotationType;
use Doctrine\ORM\EntityManagerInterface as EntityManager;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Ranking;

//Pour pouvoir utiliser l'annotation IsGranted
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class RankingController extends AbstractController
{
    /**
     * @Route("/ranking", name="ranking")
     */
    public function index()
    {
        return $this->render('ranking/index.html.twig', [
            'controller_name' => 'RankingController',
        ]);
    }

    /**
     * @Route("/noter/album/{id}", name="noter", requirements={"id"="\d+"})
     * @isGranted("IS_AUTHENTICATED_FULLY")
     */
    public function noter(RecordRepo $rr, EntityManager $em, Request $rq, $id){
        $user = $this->getUser();
        $album = $rr->find($id);
        $notation = new Ranking;
        $formRanking = $this->createForm(NotationType::class, $notation);
        $formRanking->handleRequest($rq);
        if($formRanking->isSubmitted() && $formRanking->isValid()){
            $notation->setUser($user);
            $notation->setRecord($album);
            $em->persist($notation);
            $em->flush();
            $this->addFlash("success", "Votre note a bien été prise en compte !");
            return $this->redirectToRoute("record_fiche", ["id" => $id]);
        }
        return $this->render("ranking/formulaire_notation.html.twig", [
            "formRanking" => $formRanking->createView(),
            "album" => $album
        ]);
    }
}
