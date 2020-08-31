<?php

namespace App\Service;

use App\Entity\Customer;
use App\Entity\Doctor;
use Doctrine\ORM\EntityManagerInterface;


class HomePageService
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

    public function registrationCodeGenerator()
    {
        $length = 20;
        return substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 1, $length);

    }
    public function appointmentCreation($form, $bytes)
    {
        $customer = new Customer();

        $customer->setCustomerFirstName(ucfirst(trim($form['firstName']->getData())));
        $customer->setCustomerReservationCode($bytes);

        $doctor_id = $form['doctors']->getData();

        $post = $this->entityManager->getRepository(Doctor::class)->find($doctor_id);
        $customer->setFkDoctor($post);
        $customer->setAppointmentTime($form['selectedTime']->getData());
        $customer->setAppointmentIsFinished(0);
        $customer->setIsInAppointment(0);
        $this->entityManager->persist($customer);
        $this->entityManager->flush();

    }

}
