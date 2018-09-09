<?php

namespace AppBundle\Controller;

use AppBundle\Document\Product;
use AppBundle\Service\ScenarioService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ScenarioController extends Controller
{
    public function ajaxCheckForRefreshAction()
    {
        if ($this->getScenarioService()->cameraShouldRefresh()) {
            return new Response(1);
        } else {
            return new Response(0);
        }
    }

    public function ajaxGetProductsAction(Request $request)
    {
        switch ($request->get('scenario')) {
            case ScenarioService::SCENARIO_PERSON;
                $productIds = $this->getProductIdsForEmail($request->get('email'));
                break;
            case ScenarioService::SCENARIO_CAMERA;
                $productIds = $this->getScenarioService()->getRecommendationsForScenarioCamera();
                break;
            case ScenarioService::SCENARIO_ALL;
            default:
                $productIds = $this->getScenarioService()->getRecommendationsForScenarioAll();
        }

        $products = $this->getProductRepo()->getProducts($productIds);

        if (empty($products)) {
            return new Response('', 404);
        }

        return $this->render('@App/Scenario/widgets/ajax-products.html.twig', ['products' => $products]);
    }

    public function allAction()
    {
        $productIds = $this->getScenarioService()->getRecommendationsForScenarioAll();

        $products = $this->getProductRepo()->getProducts($productIds);

        return $this->render('@App/Scenario/all.html.twig', ['products' => $products]);
    }

    public function personAction(Request $request)
    {
        $form = $this->createPersonForm();
        $form->handleRequest($request);

        $productIds = $this->getProductIdsForEmail($form->getData()['email'] ?? '');

        $products = $this->getProductRepo()->getProducts($productIds);

        return $this->render('@App/Scenario/person.html.twig', [
            'products' => $products,
            'form' => $form->createView(),
            'showReload' => $form->isSubmitted()
        ]);
    }

    public function cameraAction()
    {
        $productIds = $this->getScenarioService()->getRecommendationsForScenarioCamera();

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

    private function createPersonForm(): FormInterface
    {
        $builder = $this->createFormBuilder()->setMethod('POST');

        $builder
            ->add(
                'email',
                EmailType::class,
                [
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'Email'
                    ],
                    'label' => 'Enter the email of your eMAG account to show you better recommendations'
                ]
            )
            ->add(
                'checkbox',
                CheckboxType::class,
                [
                    'label' => "I understand and agree that my personal data won't be stored nor used in any way except for this current page load (to show profiled recommendations)",
                ]
            )
            ->add(
                'submit',
                SubmitType::class,
                [
                    'attr' => ['class' => 'btn btn-lg btn-primary btn-block'],
                    'label' => 'Show me better recommendations'
                ]
            );

        return $builder->getForm();
    }

    /**
     * @param $email
     * @return array
     */
    private function getProductIdsForEmail($email): array
    {
        if (!empty($email) && $email = filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $productIds = $this->getScenarioService()->getRecommendationsForScenarioPerson($email);
        } else {
            $productIds = $this->getScenarioService()->getRecommendationsForScenarioAll();
        }
        return $productIds;
    }
}