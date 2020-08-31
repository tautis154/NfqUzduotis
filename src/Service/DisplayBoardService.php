<?php

namespace App\Service;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;


class DisplayBoardService
{
    private $entityManager;
    private $customerRepository;

    /**
     * SpecialistService constructor.
     * @param EntityManagerInterface $entityManager
     * @param CustomerRepository $customerRepository
     */
    public function __construct(EntityManagerInterface $entityManager, CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
        $this->entityManager = $entityManager;
    }

    public function getCustomerReservationCode($var)
    {
        return $this->entityManager->getRepository(Customer::class)
            ->findBy([
                'customerReservationCode' => $var
            ]);
    }

    public function getCustomersInAppointment()
    {
        $customerInAppointmentId = $this->customerRepository->getCustomersInAppointmentId();

        return $this->entityManager->getRepository(Customer::class)
            ->findBy([
                'id' => $customerInAppointmentId
            ]);
    }
    public function getCustomersInAppointDoctorFirstName($customersInAppointment)
    {
        $doctorFirstNames = array();
        foreach ($customersInAppointment as $customer) {
            $doctorFirstNames[] = ($customer->getFkDoctor()->getDoctorFirstName());
        }
        return $doctorFirstNames;
    }

    public function getUpcomingCustomersAppointment()
    {
        $upcomingCustomerAppointmentId = $this->customerRepository->getUpcomingCustomersAppointmentId();

        return $this->entityManager->getRepository(Customer::class)
            ->findBy(
                [
                    'id' => $upcomingCustomerAppointmentId,
                ],
                [
                    'appointmentTime' => 'ASC'
                ]
            );
    }

    public function getUpcomingCustomersDoctor($upcomingCustomersAppointment)
    {
        $doctorFirstNamesUpcomingVisit = array();
        foreach ($upcomingCustomersAppointment as $customer) {
            $doctorFirstNamesUpcomingVisit[] = ($customer->getFkDoctor()->getDoctorFirstName());
        }
        return $doctorFirstNamesUpcomingVisit;
    }

    public function getTimesLeftForCustomers($upcomingCustomersAppointment)
    {
        $timeLeftForCustomer = array();

        foreach ($upcomingCustomersAppointment as $upcomingCustomer) {
            $customerAppointmentTime = $upcomingCustomer->getAppointmentTime();

            $now = date('Y-m-d h:i:s', time());
            try {
                $x = new DateTime($now);
            } catch (Exception $e) {
            }
            $x = $x->diff($customerAppointmentTime);

            if (1 === $x->invert) {
                $timeLeftForCustomer[] = 0;
            } else {
                $timeLeftForCustomer[] = $x->format("%Y Years %M Months %D Days %H:%I.%S");
            }
        }

        return $timeLeftForCustomer;
    }

}
