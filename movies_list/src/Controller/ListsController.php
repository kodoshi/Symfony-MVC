<?php

namespace App\Controller;

use App\Entity\Lists;
use App\Entity\User;
use App\Form\ListsFormType;
use App\Repository\ListsRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;


/**
 * @Route("/lists")
 */

class ListsController extends AbstractController
{
    /**
     * @Route("/", name="lists_index", methods={"GET"})
     */
    public function index(ListsRepository $listsRepository): Response
    {
        $user = $this->getUser();

        return $this->render('lists/index.html.twig', [
            'lists' => $listsRepository->findAll(),
            'user' => $user,
            ]);

    }

    /**
     * @Route("/{id}/show", name="lists_show", methods={"GET"})
     * @param Lists $list
     * @return Response
     */
    public function show(Lists $list): Response
    {
        return $this->render('lists/show.html.twig', [
            'list' => $list,
        ]);
    }

    /**
     * @Route("/new", name="lists_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $list = new Lists();
        $list->setAuthor($this->getUser());
        $form = $this->createForm(ListsFormType::class, $list);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($list);
            $entityManager->flush();

            return $this->redirectToRoute('lists_index');
        }

        return $this->render('lists/new.html.twig', [
            'list' => $list,
            'form' => $form->createView(),
            ]);
    }

    /**
     * @Route("/{id}/edit", name="lists_edit", methods={"GET", "POST"})
     * @param Request $request
     * @param Lists $list
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function edit(Request $request, Lists $list)
    {
        $form = $this->createForm(ListsFormType::class, $list);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('lists_index', [
                'id' => $list->getId(),
            ]);
        }

        return $this->render('lists/edit.html.twig', [
            'list' => $list,
            'form' => $form->createView(),
        ]);
    }
}