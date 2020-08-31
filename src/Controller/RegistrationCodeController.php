<?php

namespace App\Controller;

use App\Form\CancelType;
use App\Form\RegistrationCodeType;
use App\Service\RegistrationCodeService;
use App\Service\TimeDifferenceService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationCodeController extends AbstractController
{
    /**
     * @Route("/registration/code", name="registration_code")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function index(Request $request)
    {
        $form = $this->createForm(RegistrationCodeType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('search', $form->getData());
        }

        return $this->render('registration_code/index.html.twig', ['form' => $form->createView()]);
    }


    /**
     * @Route("/search", name="search")
     * @param Request $request
     * @param TimeDifferenceService $timeDifference
     * @param RegistrationCodeService $registrationCodeService
     * @return Response
     */
    public function results(Request $request, TimeDifferenceService $timeDifference, RegistrationCodeService $registrationCodeService)
    {
        $var = $request->get('registrationCode');

        $customers=$registrationCodeService->getCustomerReservationCode($var);

        if (empty($customers)) {
            $this->addFlash('warning', 'No such registration code exists, Try entering it again');
            return $this->redirectToRoute('registration_code');
        }

        $customerFirstName = $customers[0]->getCustomerFirstName();

        $customerTimeLeft = $timeDifference->timeDifferenceCalculator($customers);

        $form = $this->createForm(CancelType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $registrationCodeService->cancelCustomer($var);

            $this->addFlash('success', 'Your registration is successfully cancelled');
            return $this->redirectToRoute('home');
        }
        return $this->render('registration_code/results.html.twig', [
            'form' => $form->createView(),
            'customerName' => $customerFirstName,
            'remainingTime' => $customerTimeLeft]);
    }
}
