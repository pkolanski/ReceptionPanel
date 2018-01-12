<?php

namespace AppBundle\Controller;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\Reservation;


class ReservationsController extends Controller
{
    /**
     * @Route("/reservations", name="reservations")
     */
    public function reservationsAction()
    {
        $reservations=$this->getDoctrine()
            ->getRepository('AppBundle:Reservation')
            ->findAll();
        return $this->render('reservations/reservations.html.twig',array('reservations'=>$reservations));
    }

    /**
     * @Route("/reservations/create", name="reservation-create")
     */
    public function createAction(Request $request)
    {
        $reservation=new Reservation;
        $now=new\DateTime('now');

        $reservation->setToDate($now)
            ->setFromDate($now);

        $form=$this->createFormBuilder($reservation)
            ->add('firstName',TextType::class,array('attr'=>array('class'=>'form-control','style'=>'margin-bottom:15px')))
            ->add('lastName',TextType::class,array('attr'=>array('class'=>'form-control','style'=>'margin-bottom:15px')))
            ->add('roomNo',TextType::class,array('attr'=>array('class'=>'form-control','style'=>'margin-bottom:15px')))
            ->add('fromDate',DateTimeType::class,array('attr'=>array('style'=>'margin-bottom:15px')))
            ->add('toDate',DateTimeType::class,array('attr'=>array('style'=>'margin-bottom:15px')))
            ->add('create reservation',SubmitType::class,array('attr'=>array('class'=>'btn btn-success','style'=>'margin-bottom:15px')))
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $firstName=$form['firstName']->getData();
            $lastName=$form['lastName']->getData();
            $roomNo=$form['roomNo']->getData();
            $fromDate=$form['fromDate']->getData();
            $toDate=$form['toDate']->getData();


            $reservation->setFirstName($firstName)
                        ->setLastName($lastName)
                        ->setFromDate($fromDate)
                        ->setToDate($toDate)
                        ->setRoomNo($roomNo)
                        ->setReservationDate($now);

            $em=$this->getDoctrine()->getManager();
            $em->persist($reservation);
            $em->flush();

            $this->addFlash(
                'notice',
                'Reservation Added'
            );
            return $this->redirectToRoute('reservations');
        }
        return $this->render('reservations/create.html.twig',array('form'=>$form->createView()));
    }
    /**
     * @Route("/reservations/edit/{id}", name="reservation-edit")
     */
    public function editAction($id,Request $request)
    {
        $reservation = $this->getDoctrine()
            ->getRepository('AppBundle:Reservation')
            ->find($id);
        $now=new\DateTime('now');

        $reservation->setFirstName($reservation->getFirstName())
            ->setLastName($reservation->getLastName())
            ->setFromDate($reservation->getFromDate())
            ->setToDate($reservation->getToDate())
            ->setRoomNo($reservation->getRoomNo())
            ->setReservationDate($now);

        $form=$this->createFormBuilder($reservation)
            ->add('firstName',TextType::class,array('attr'=>array('class'=>'form-control','style'=>'margin-bottom:15px')))
            ->add('lastName',TextType::class,array('attr'=>array('class'=>'form-control','style'=>'margin-bottom:15px')))
            ->add('roomNo',TextType::class,array('attr'=>array('class'=>'form-control','style'=>'margin-bottom:15px')))
            ->add('fromDate',DateTimeType::class,array('attr'=>array('style'=>'margin-bottom:15px')))
            ->add('toDate',DateTimeType::class,array('attr'=>array('style'=>'margin-bottom:15px')))
            ->add('save',SubmitType::class,array('attr'=>array('label'=>'Save reservation','class'=>'btn btn-success','style'=>'margin-bottom:15px')))
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $firstName=$form['firstName']->getData();
            $lastName=$form['lastName']->getData();
            $roomNo=$form['roomNo']->getData();
            $fromDate=$form['fromDate']->getData();
            $toDate=$form['toDate']->getData();


            $em=$this->getDoctrine()->getManager();
            $reservation=$em->getRepository('AppBundle:Reservation')->find($id);

            $reservation->setFirstName($firstName)
                ->setLastName($lastName)
                ->setFromDate($fromDate)
                ->setToDate($toDate)
                ->setRoomNo($roomNo)
                ->setReservationDate($now);

            $em->persist($reservation);
            $em->flush();

            $this->addFlash(
                'notice',
                'Reservation Updated'
            );
            return $this->redirectToRoute('reservations');
        }

        return $this->render('reservations/edit.html.twig',array('reservation'=>$reservation,'form'=>$form->createView()));
    }
    /**
     * @Route("/reservations/delete/{id}", name="reservation-delete")
     */
    public function deleteAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $reservation=$em->getRepository('AppBundle:Reservation')->find($id);

        $em->remove($reservation);
        $em->flush();

        $this->addFlash(
            'error',
            'Reservation Removed'
        );
        return $this->redirectToRoute('reservations');
    }
}
