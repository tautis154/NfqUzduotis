<?php

namespace App\Controller;

use App\Form\BoardControllerType;

use App\Service\DisplayBoardService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DisplayBoardController extends AbstractController
{
    /**
     * @Route("/display/board", name="display_board")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function index(Request $request)
    {
        $form = $this->createForm(BoardControllerType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('displayBoard', $form->getData());
        }

        return $this->render('display_board/index.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/displayBoard", name="displayBoard")
     * @param Request $request
     * @param DisplayBoardService $displayBoard
     * @return Response
     * @throws \Exception
     */
    public function results(Request $request, DisplayBoardService $displayBoard)
    {

        $var = $request->get('registrationCode');

        $customers = $displayBoard->getCustomerReservationCode($var);

        if (empty($customers)) {
            $this->addFlash('warning', 'No such registration code exists, Try entering it again');
            return $this->redirectToRoute('display_board');
        }

        $customersInAppointment = $displayBoard->getCustomersInAppointment();
        $doctorFirstNames = $displayBoard->getCustomersInAppointDoctorFirstName($customersInAppointment);

        $upcomingCustomersAppointment = $displayBoard->getUpcomingCustomersAppointment();
        $doctorFirstNamesUpcomingVisit = $displayBoard->getUpcomingCustomersDoctor($upcomingCustomersAppointment);

        $timeLeftForCustomer = $displayBoard->getTimesLeftForCustomers($upcomingCustomersAppointment);


        return $this->render('display_board/board.html.twig', [
            'customers' => $customersInAppointment,
            'doctorFirstNames' => $doctorFirstNames,
            'upcomingCustomers' => $upcomingCustomersAppointment,
            'doctorFirstNamesUpcomingVisit' => $doctorFirstNamesUpcomingVisit,
            'timeLeftForCustomers' => $timeLeftForCustomer]);
    }
}
