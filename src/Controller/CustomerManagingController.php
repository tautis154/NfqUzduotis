<?php

namespace App\Controller;

use App\Service\CustomerManagementService;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CustomerManagingController extends AbstractController
{
    /**
     * @Route("/customer/managing", name="customer_managing")
     */
    public function index()
    {
        if (!$this->getUser()) {
            $this->addFlash('danger', 'Please log in');
            return $this->redirectToRoute('login');
        }

        $user = $this->getUser();

        $customers = $user->getCustomers();

        $customerInAppointmentId = null;
        $atleastOneAppointedCustomer = 0;

        foreach ($customers as $customer) {
            if ($customer->getIsInAppointment() == '1') {
                $customerInAppointmentId = $customer->getId();
                $atleastOneAppointedCustomer = 1;
                break;
            }
        }

        return $this->render('customer_managing/index.html.twig', [
            'customers' => $customers,
            'doctor' => $user,
            'customerIsInAppointmentId' => $customerInAppointmentId,
            'atleastOneAppointedCustomer' => $atleastOneAppointedCustomer]);
    }

    /**
     * @Route("/customer/delete/{id}", name="customer_delete", methods={"DELETE"})
     * @param $id
     * @param CustomerManagementService $managementService
     */
    public function delete($id, CustomerManagementService $managementService)
    {
        $managementService->deleteAppointment($id);

        $response = new Response();
        $response->send();
    }

    /**
     * @Route("/customer/updateAppointment/{id}", name="customer_update", methods={"UPDATE"})
     * @param $id
     * @param CustomerManagementService $managementService
     */
    public function updateAppointment($id, CustomerManagementService $managementService)
    {
        $managementService->updateAppointment($id);

        $response = new Response();
        $response->send();
    }

    /**
     * @Route("/customer/endAppointment/{id}", name="customer_end", methods={"END"})
     * @param $id
     * @param CustomerManagementService $managementService
     */
    public function endAppointment($id, CustomerManagementService $managementService)
    {
        $managementService->endAppointment($id);

        $response = new Response();
        $response->send();
    }
}
