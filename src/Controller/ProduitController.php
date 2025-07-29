<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\SearchType;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route("/produit")]
final class ProduitController extends AbstractController
{
    #[Route('', name: 'produit_index')]
    public function index(ProduitRepository $ProduitRepository, Request $resquest): Response
    {
        $searchData = '';
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($resquest);

        if ($form->isSubmitted() == true && $form->isValid()) {
            $searchData = $form->get('q')->getData();
            $produits = $ProduitRepository->findBySearch($searchData);
        } else {
            $produits = $ProduitRepository->findAll();
        }

        if (!$produits) {
            $this->addFlash('error', 'Aucun produit trouvé pour la recherche : ' . $searchData);
        } else {
            $this->addFlash('success', count($produits) . ' produit(s) trouvé(s) pour la recherche : ' . $searchData);
        }

        return $this->render('produit/index.html.twig', [
            'produits' => $produits,
            'form' => $form->createView()
        ]);
    }

    #[Route('show/{id}', name: 'produit_show')]
    public function show(int $id, ProduitRepository $ProduitRepository): Response
    {
        $produit = $ProduitRepository->find($id);
        if (!$produit) {
            throw $this->createNotFoundException('Produit not found');
        }
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('create', name: 'produit_create')]
    public function create(Request $resquest, EntityManagerInterface $em): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($resquest);

        if ($form->isSubmitted() == true && $form->isValid()) {

            $image = $form->get('imageFile')->getData();
            if ($image !== null ) {
                $fileName = uniqid() . '.' . $image->guessExtension();
                $image->move($this->getParameter('image_directory').'/imagesProduits', $fileName);
                $produit->setPhoto($fileName);
            }

            $em->persist($produit);
            $em->flush();

            $this->addFlash('success', 'Le produit' . $produit->getLibelle() . ' a bien été créé avec succès !');
            return $this->redirectToRoute('produit_index');
        }

        return $this->render('produit/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('update/{id}', name: 'produit_update')]
    public function update(Request $resquest, EntityManagerInterface $em, Produit $produit): Response
    {
        if (!$produit) {
            throw $this->createNotFoundException('Produit not found');
        }
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($resquest);

        if ($form->isSubmitted() == true && $form->isValid()) {

            $image = $form->get('imageFile')->getData();
            if ($image !== null) {
                // Récupérer l'ancien nom de fichier
                $oldFile = $produit->getPhoto();
                $uploadDir = $this->getParameter('image_directory') . '/imagesProduits';

                // Supprimer l'ancien fichier s'il existe
                if ($oldFile && file_exists($uploadDir . '/' . $oldFile)) {
                    unlink($uploadDir . '/' . $oldFile);
                }

                // Enregistrer la nouvelle image
                $fileName = uniqid() . '.' . $image->guessExtension();
                $image->move($uploadDir, $fileName);
                $produit->setPhoto($fileName);
            }
            
            $em->persist($produit);
            $em->flush();

            $this->addFlash('success', 'Le produit ' . $produit->getLibelle() . 'a été mis à jour avec succès !');
            return $this->redirectToRoute('produit_index');
        }

        return $this->render('produit/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
