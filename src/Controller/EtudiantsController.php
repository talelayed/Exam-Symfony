<?php

namespace App\Controller;

use App\Entity\Etudiant;
use App\Form\EtudiantType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/add', name: 'add_etudiants')]
    public function add(Request $request, ManagerRegistry $doctrine):Response{

        $etudiant = new Etudiant();
        $form = $this->createForm(EtudiantType::class);
        $form->handleRequest($request);
        $etudiant->setNom((string)$form['nom']->getData());
        $etudiant->setPrenom((string)$form['prenom']->getData());
        $etudiant->setSection($form['section']->getData());
        if($form->isSubmitted()){
            $manager = $doctrine->getManager();
            $manager->persist($etudiant);
            $manager->flush();
            $this->addFlash("success", "student added successfully");
            return $this->redirectToRoute("etudiants");
        }
        return $this->render('form.html.twig',[
            'form' => $form->createView()
        ]);
    }

    #[Route('/edit/{id}', name: 'edit_etudiant')]
    public function edit(Etudiant $etudiant, Request $request, ManagerRegistry $doctrine):Response{

        $form = $this->createForm(EtudiantType::class);
        $form->handleRequest($request);
        if((string)$form['nom']->getData()) {
            $etudiant->setNom((string)$form['nom']->getData());
        }
        if((string)$form['prenom']->getData()) {
            $etudiant->setPrenom((string)$form['prenom']->getData());
        }
        if($form['section']->getData()) {
            $etudiant->setSection($form['section']->getData());
        }
        if($form->isSubmitted()){
            $manager = $doctrine->getManager();
            $manager->persist($etudiant);
            $manager->flush();
            $this->addFlash("edited", "student edited successfully");
            return $this->redirectToRoute("etudiants");
        }
        return $this->render('form.html.twig',[
            'form' => $form->createView()
        ]);
    }

    #[Route('/delete/{id}', name: 'delete_etudiant')]
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
