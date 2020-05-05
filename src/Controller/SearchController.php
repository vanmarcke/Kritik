<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ArtistRepository;
use App\Repository\RecordRepository as RecordRepo;

class SearchController extends AbstractController
{
    /**
     * @Route("/search", name="search")
     */
    public function index(Request $rq, ArtistRepository $ar, RecordRepo $rr )
    {
        $motRecherche = $rq->query->get("mot");
        $motRecherche = trim($motRecherche);
        if($motRecherche){
            $artistes = $ar->findByName($motRecherche);
            $albums = $rr->findByTitle($motRecherche);      
            // return $this->render('search/index.html.twig', [ 'artistes' => $artistes, "albums" => $albums ]);
            return $this->render('search/index.html.twig', compact("artistes", "albums"));
        }
        else{
            return $this->redirectToRoute("home");
        }
        

    }

}
