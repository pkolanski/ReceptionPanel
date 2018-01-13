<?php

namespace AppBundle\Controller;

use FOS\UserBundle\Controller\ProfileController as BaseController;
use FOS\UserBundle\Model\UserInterface;

class ProfileController extends BaseController
{
    public function showAction()
    {
        $user = $this->getUser();

        $reservations=$this->getDoctrine()
            ->getRepository('AppBundle:Reservation')
            ->findAll();

        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        return $this->render('@FOSUser/Profile/show.html.twig', array(
            'user' => $user,
            'reservations'=>$reservations
        ));
    }
}
