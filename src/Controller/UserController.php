<?php

namespace App\Controller;

use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface as EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function index(UserRepository $ur)
    {
        $users = $ur->findall();
        return $this->render('user/index.html.twig', compact("users"));
    }

    /**
     * @Route("/user/modifier/{id}", name="user_modifier", requirements={"id"="\d+"})
     */
    public function modifier(UserRepository $ur, EntityManager $em, Request $rq, UserPasswordEncoderInterface $up, int $id){
        $user = $ur->find($id);
        $formUser = $this->createform(UserType::class, $user);
        $formUser->handleRequest($rq);
        if($formUser->isSubmitted() && $formUser->isValid()){
            $mdp = $formUser->get("password")->getData();
            if($mdp){
                $mdp = $up->encodePassword($user, $mdp);
                $user->setPassword($mdp);
            }            
            $em->persist($user);
            $em->flush();
            $this->addFlash("success", "L'utilisateur n° " . $user->getId() . " a bien été modifié !");
            return $this->redirectToRoute("user");
        }
        return $this->render("user/formulaire.html.twig", [
            "formUser" => $formUser->createView()
        ]);
    }

    /**
     * @Route("/user/supprimer/{id}", name="user_supprimer", requirements={"id"="\d+"})
     */
    public function supprimer(UserRepository $ur, EntityManager $em, int $id){
        $user = $ur->find($id);
        $em->remove($user);
        $em->flush();
        $this->addflash("success", "Le membre N°$id a bien été supprimé !");
        return $this->redirectToRoute("user");
    }

}
