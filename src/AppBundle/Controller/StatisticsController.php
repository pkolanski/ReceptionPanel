<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;


class StatisticsController extends Controller
{
    /**
     * @Route("/statistics", name="statistics")
     */
    public function statisticsAction()
    {
        return $this->render('statistics/statistics.html.twig');//, array('name' => $name));
    }
}
