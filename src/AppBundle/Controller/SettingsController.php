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
        1 => 'Showroom Bucharest',
        2 => 'Showroom Las Vegas',
        3 => 'Showroom New York',
        4 => 'Showroom London',
        5 => 'Showroom Bumbai',
        6 => 'Showroom Hong Kong',
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
                    'choices' => array_flip($this->getAvailableShowrooms()),
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
                    'choices' => array_flip($this->getAvailableCameras()),
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

    private function getAvailableShowrooms(): array
    {
        return self::SHOWROOMS;
    }

    private function getAvailableCameras(): array
    {
        // todo: cameras should be childs of showrooms
        return [
            1 => 'Camera #1 (TV Apple zone)',
            2 => 'Camera #2 (TV Samsung zone)',
            3 => 'Camera #3 (TV MDA)',
            4 => 'Camera #4 (TV Notebooks)',
        ];
    }

    private function getSettingsService(): SettingsService
    {
        return $this->get('settings.service');
}

}
