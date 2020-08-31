<?php

namespace App\Service;

use App\Entity\Customer;
use Doctrine\ORM\EntityManagerInterface;



class CustomerManagementService
{
    private $entityManager;

    /**
     * SpecialistService constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function deleteAppointment($id)
    {
        $customer = $this->entityManager->getRepository(Customer::class)->find($id);
        $this->entityManager->remove($customer);
        $this->entityManager->flush();
    }

    public function updateAppointment($id)
    {
        $customer = $this->entityManager->getRepository(Customer::class)->find($id);
        $customer->setIsInAppointment('1');
        $this->entityManager->persist($customer);
        $this->entityManager->flush();
    }

    public function endAppointment($id)
    {
        $customer = $this->entityManager->getRepository(Customer::class)->find($id);
        $customer->setIsInAppointment('0');
        $customer->setAppointmentIsFinished('1');
        $this->entityManager->persist($customer);
        $this->entityManager->flush();
    }


}
