<?php

namespace App\Controller;

use App\Entity\Etudiant;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/etudiants')]
class EtudiantsController extends AbstractController
{
    #[Route('/', name: 'etudiants')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $manager = $doctrine->getRepository(Etudiant::class);
        $etudiants=$manager->findAll();
        return $this->render('etudiants/index.html.twig', [
            'controller_name' => 'EtudiantsController',
            'etudiants'=>$etudiants,
        ]);
    }
#[Route('/add/{nom}/{prenom}', name: 'add_etudiants')]
public function add(ManagerRegistry $doctrine,$nom,$prenom):Response{
        $manager=$doctrine->getManager();
        $etudiant = new Etudiant();
        $etudiant->setNom($nom);
        $etudiant->setPrenom($prenom);
        $manager->persist($etudiant);
        $manager->flush();
    $this->addFlash("success", "student added successfully");
        return $this->redirectToRoute("etudiants");
    }

    #[Route('/delete/{id}', name: 'delete_etudiants')]
    public function delete(ManagerRegistry $doctrine,Etudiant $etudiant):Response
    {
        $manager = $doctrine->getManager();
        $managerReg = $doctrine->getRepository(Etudiant::class);
        if ($managerReg->find($etudiant->getId())) {
            $manager->remove($etudiant);
            $manager->flush();
            $this->addFlash("deleted", "student deleted successfully");
        } else {
            $this->addFlash("error", "student non existing");
        }
        return $this->redirectToRoute("etudiants");
    }
}
