<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

/**
 * @Route("/profile")
 */

class ProfileController extends AbstractController
{
 
/**
 * @Route("/{id}", name="profile_show")
 */
    public function show(): Response
    {
        $user = $this->getUser();

        return $this->render('profile/show.html.twig', [
            'user' => $user,

        ]);
    }
    /**
     * @Route ("/{id}/edit", name="profile_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, UserPasswordEncoderInterface $passwordEncoder) : Response
    {
        $user = $this->getUser();

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()&& $form->isValid()) {
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('profile_show', [
                'id' => $user->getId(),

            ]);
        }
        return $this->render("profile/edit.html.twig", [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route ("/{id}/delete", name="profile_delete")
     */
    public function deleteUser(Request $request) : Response
    {
        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
        $this->get('security.token_storage')->setToken(null);
        $request->getSession()->invalidate();
        
        return $this->redirectToRoute("main");

    }
}
