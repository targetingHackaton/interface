<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class EmulatorController extends Controller
{
    public function indexAction(Request $request)
    {
        $form = $this->getEmulatorForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            dump($data);
            $this->sendRequest($data['showroomId'], $data['endpoint'], $data['cameraId'], $data['age'], $data['gender']);
        }

        return $this->render('@App/Emulator/index.html.twig', ['form' => $form->createView()]);
    }

    public function counterAction()
    {
        $data = $this->get('scenario.service')->getRawResponseFromApi('getShowroomCounter');

        return $this->render('@App/Emulator/counter.html.twig', ['data' => $data]);
    }

    private function getEmulatorForm(): FormInterface
    {
        $builder = $this->createFormBuilder()->setMethod('POST');

        $builder
            ->add(
                'showroomId',
                ChoiceType::class,
                [
                    'attr' => [
                        'class' => 'form-control',
                    ],
                    'choices' => array_flip(SettingsController::SHOWROOMS),
                    'label' => 'Showroom ID'
                ]
            )
            ->add(
                'cameraId',
                ChoiceType::class,
                [
                    'attr' => [
                        'class' => 'form-control',
                    ],
                    'choices' => array_flip(SettingsController::AVAILABLE_CAMERAS),
                    'label' => 'Camera ID'
                ]
            )
            ->add(
                'endpoint',
                ChoiceType::class,
                [
                    'attr' => [
                        'class' => 'form-control',
                    ],
                    'choices' => [
                        'in' => 'in',
                        'out' => 'out',
                        'front' => 'front',
                    ],
                    'label' => 'Endpoint'
                ]
            )
            ->add(
                'age',
                ChoiceType::class,
                [
                    'attr' => [
                        'class' => 'form-control',
                    ],
                    'choices' => array_flip(SettingsController::AGE_AVERAGES),
                    'label' => 'Age (averages)'
                ]
            )
            ->add(
                'gender',
                ChoiceType::class,
                [
                    'attr' => [
                        'class' => 'form-control',
                    ],
                    'choices' => [
                        'M' => 'M',
                        'F' => 'F',
                    ],
                    'label' => 'Gender'
                ]
            )
            ->add(
                'submit',
                SubmitType::class,
                [
                    'attr' => ['class' => 'btn btn-lg btn-primary btn-block mt-5'],
                    'label' => 'Send request'
                ]
            );

        return $builder->getForm();
    }

    private function sendRequest($showroomId, $endpoint, $cameraId, $age, $gender)
    {
        $service = $this->get('scenario.service');

        $path = $endpoint;
        $dataToSend = ['showroomId' => $showroomId];

        if (in_array($endpoint, ['in', 'out', 'front'])) {
            $dataToSend['age'] = $age;
            $dataToSend['gender'] = $gender;
        }

        if ($endpoint == 'front') {
            $dataToSend['cameraId'] = $cameraId;
        }

        $service->getRawResponseFromApi($path, $dataToSend);
    }

}
