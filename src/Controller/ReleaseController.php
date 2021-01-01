<?php

namespace App\Controller;

use App\Entity\Release;
use App\Form\ReleaseType;
use App\Repository\ReleaseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/release")
 */
class ReleaseController extends AbstractController
{
    /**
     * @Route("/", name="release_index", methods={"GET"})
     */
    public function index(ReleaseRepository $releaseRepository): Response
    {
        return $this->render('release/index.html.twig', [
            'releases' => $releaseRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="release_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $release = new Release();
        if ($request->get('title')) {
            $release->setTitle($request->get('title'));
        }
        if ($request->get('catalogNumber')) {
            $release->setCatalogNumber($request->get('catalogNumber'));
        }
        if ($request->get('barcode')) {
            $release->setBarcode($request->get('barcode'));
        }
        if ($request->get('genre')) {
            $release->setGenre($request->get('genre'));
        }
        if ($request->get('released')) {
            $release->setReleaseDate(new \DateTime($request->get('released')));
        }
        if ($request->get('artist')) {
            $release->setReleaseDate($request->get('artist'));
        }
        $form = $this->createForm(ReleaseType::class, $release);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $release->setUser($this->getUser());
            $release->setAddedDate(new \DateTime());
            $entityManager->persist($release);
            $entityManager->flush();

            return $this->redirectToRoute('release_index');
        }

        return $this->render('release/new.html.twig', [
            'release' => $release,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="release_show", methods={"GET"})
     */
    public function show(Release $release): Response
    {
        return $this->render('release/show.html.twig', [
            'release' => $release,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="release_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Release $release): Response
    {
        $form = $this->createForm(ReleaseType::class, $release);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('release_index');
        }

        return $this->render('release/edit.html.twig', [
            'release' => $release,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="release_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Release $release): Response
    {
        if ($this->isCsrfTokenValid('delete'.$release->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($release);
            $entityManager->flush();
        }

        return $this->redirectToRoute('release_index');
    }
}