<?php

namespace AppBundle\Controller;

use AppBundle\Service\SettingsService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class SettingsController extends Controller
{
    const SHOWROOMS = [
        1 => '(1) Showroom Bucharest',
        2 => '(2) Showroom Las Vegas',
        3 => '(3) Showroom New York',
        4 => '(4) Showroom London',
        5 => '(5) Showroom Bumbai',
        6 => '(6) Showroom Hong Kong',
    ];

    // todo: cameras should be childs of showrooms
    const AVAILABLE_CAMERAS = [
        1 => '(1) Camera TV Apple zone',
        2 => '(2) Camera TV Samsung zone',
        3 => '(3) Camera TV MDA',
        4 => '(4) Camera TV Notebooks',
    ];

    const AGE_AVERAGES = [
        1 => 'v1',
        2 => 'v2',
        3 => 'v3',
        4 => 'v4',
        5 => 'v5',
    ];

    public function indexAction(Request $request)
    {
        $form = $this->getSettingsForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $this->getSettingsService()->saveShowroom($data['showroom']);
            $this->getSettingsService()->saveCamera($data['camera']);
        }

        return $this->render('@App/Settings/index.html.twig', ['form' => $form->createView()]);
    }

    private function getSettingsForm(): FormInterface
    {
        $builder = $this->createFormBuilder()->setMethod('POST');

        $builder
            ->add(
                'showroom',
                ChoiceType::class,
                [
                    'attr' => [
                        'class' => 'form-control',
                    ],
                    'choices' => array_flip(self::SHOWROOMS),
                    'data' => $this->getSettingsService()->getShowroom(),
                    'label' => 'Change Showroom'
                ]
            )
            ->add(
                'camera',
                ChoiceType::class,
                [
                    'attr' => [
                        'class' => 'form-control',
                    ],
                    'choices' => array_flip(self::AVAILABLE_CAMERAS),
                    'data' => $this->getSettingsService()->getCamera(),
                    'label' => 'Change Camera'
                ]
            )
            ->add(
                'submit',
                SubmitType::class,
                [
                    'attr' => ['class' => 'btn btn-lg btn-primary btn-block mt-5'],
                    'label' => 'Save'
                ]
            );

        return $builder->getForm();
    }

    private function getSettingsService(): SettingsService
    {
        return $this->get('settings.service');
    }

}
