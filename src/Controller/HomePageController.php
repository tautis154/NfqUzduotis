<?php

namespace App\Controller;

use App\Form\RegistrationType;
use App\Service\HomePageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomePageController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param Request $request
     * @param HomePageService $homePageService
     * @return RedirectResponse|Response
     */
    public function index(Request $request, HomePageService $homePageService)
    {

        $form = $this->createForm(RegistrationType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $bytes = $homePageService->registrationCodeGenerator();
            $homePageService->appointmentCreation($form, $bytes);

            $this->addFlash('success', 'This is your Registration code -    ' . $bytes . ' - Please save it immediately');
            return $this->redirect($this->generateUrl('home'));
        }

        return $this->render('home/index.html.twig', ['form' => $form->createView()]);
    }
}
