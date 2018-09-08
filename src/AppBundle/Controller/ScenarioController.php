<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ScenarioController extends Controller
{
    public function listingAction()
    {
        return $this->render('@App/Scenario/listing.html.twig', []);
    }

    public function allAction()
    {
        return $this->render('@App/Scenario/all.html.twig', []);
    }

    public function personalAction()
    {
        return $this->render('@App/Scenario/personal.html.twig', []);
    }

    public function cameraAction()
    {
        return $this->render('@App/Scenario/camera.html.twig', []);
    }
}