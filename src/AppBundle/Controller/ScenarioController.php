<?php

namespace AppBundle\Controller;

use AppBundle\Document\Product;
use AppBundle\Service\ScenarioService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ScenarioController extends Controller
{
    public function listingAction()
    {
        return $this->render('@App/Scenario/listing.html.twig', []);
    }

    public function allAction()
    {
        $productIds = $this->getScenarioService()->getRecommendationsForScenarioAll();

        $products = $this->getProductRepo()->getProducts($productIds);

        return $this->render('@App/Scenario/all.html.twig', ['products' => $products]);
    }

    public function personalAction(Request $request)
    {
        $email = $request->get('email');

        $productIds = $this->getScenarioService()->getRecommendationsForScenarioPersonal($email);

        $products = $this->getProductRepo()->getProducts($productIds);

        return $this->render('@App/Scenario/personal.html.twig', ['products' => $products]);
    }

    public function cameraAction(Request $request)
    {
        $tvId = $request->get('tv_id');

        $productIds = $this->getScenarioService()->getRecommendationsForScenarioCamera($tvId);

        $products = $this->getProductRepo()->getProducts($productIds);

        return $this->render('@App/Scenario/camera.html.twig', ['products' => $products]);
    }

    private function getScenarioService(): ScenarioService
    {
        return $this->get('scenario.service');
    }

    /**
     * @return \AppBundle\Document\ProductRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    private function getProductRepo()
    {
        return $this->get('doctrine_mongodb')->getRepository(Product::class);
    }
}