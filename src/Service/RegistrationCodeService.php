<?php

namespace App\Service;

use App\Entity\Customer;
use Doctrine\ORM\EntityManagerInterface;



class RegistrationCodeService
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

    public function getCustomerReservationCode($var)
    {
        return $this->entityManager->getRepository(Customer::class)
            ->findBy([
                'customerReservationCode' => $var
            ]);
    }

    public function cancelCustomer($var)
    {
        $cancelledCustomer = $this->entityManager->getRepository(Customer::class)
            ->findOneBy([
                'customerReservationCode' => $var
            ]);

        $this->entityManager->remove($cancelledCustomer);

        $this->entityManager->flush();
    }

}
