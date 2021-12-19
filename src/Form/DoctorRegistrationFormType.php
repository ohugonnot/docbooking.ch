<?php

namespace App\Form;

use App\Entity\Doctor;
use App\Service\Translator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;


class DoctorRegistrationFormType extends AbstractType
{
    private Translator $translator;

    /**
     * @param Translator $translator
     */
    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
			->add('title', TextType::class, [
				/*'choices'  => [
					'Select' 			=> null,
					'Dr.' 				=>	'Dr.',
					'Pr.'			   	=>	'Pr.'
				],*/
				'label' => $this->translator->trans('Title'),
				'row_attr' => ['class' => 'form-group form-focus'],
				'label_attr' => ['class' => 'focus-label'],
				'attr' => ['class' => 'form-control floating'],
				'required'	=>	false
				
			])
			->add('first_name', TextType::class, [
				'label' => $this->translator->trans('First Name'),
				'row_attr' => ['class' => 'form-group form-focus'],
				'label_attr' => ['class' => 'focus-label'],
				'attr' => ['class' => 'form-control floating']
			])
			->add('last_name', TextType::class, [
				'label' => $this->translator->trans('Last Name'),
				'row_attr' => ['class' => 'form-group form-focus'],
				'label_attr' => ['class' => 'focus-label'],
				'attr' => ['class' => 'form-control floating']
			])
			->add('gender', ChoiceType::class, [
				'choices'  => [
					'Select' => null,
                    $this->translator->trans('Male') => 'Male',
                    $this->translator->trans('Female') => 'Female',
				],
				'label' => $this->translator->trans('Gender'),
				'row_attr' => ['class' => 'form-group form-focus'],
				'label_attr' => ['class' => 'focus-label'],
				'attr' => ['class' => 'form-control floating']
			])
			->add('phone_number', TextType::class, [
				'label' => $this->translator->trans('Phone or Mobile Number'),
				'row_attr' => ['class' => 'form-group form-focus'],
				'label_attr' => ['class' => 'focus-label'],
				'attr' => ['class' => 'form-control floating']
			])
			->add('address_line_1', TextType::class, [
				'label'      => $this->translator->trans('Address Line 1'),
				'row_attr'   => ['class' => 'form-group form-focus'],
				'label_attr' => ['class' => 'focus-label'],
				'attr'       => ['class' => 'form-control floating'],
				'required'   => false
			])
			->add('address_line_2', TextType::class, [
				'label' => $this->translator->trans('Address Line 2'),
				'row_attr' => ['class' => 'form-group form-focus'],
				'label_attr' => ['class' => 'focus-label'],
				'attr' => ['class' => 'form-control floating'],
				'required'=>false
			])
			->add('city', TextType::class, [
				'label' => $this->translator->trans('City'),
				'row_attr' => ['class' => 'form-group form-focus'],
				'label_attr' => ['class' => 'focus-label'],
				'attr' => ['class' => 'form-control floating']
			])
	/*
		->add('state', ChoiceType::class, [
				'choices' => [
					'Select' => null,
					'AR' => 'AR',
					'AI' => 'AI',
					'AG' => 'AG',
					'BL' => 'BL',
					'BS' => 'BS',
					'BE' => 'BE',
					'FR' => 'FR',
					'GE' => 'GE',
					'GL' => 'GL',
					'GR' => 'GR',
					'JU' => 'JU',
					'LU' => 'LU',
					'NE' => 'NE',
					'NW' => 'NW',
					'OW' => 'OW',
					'SG' => 'SG',
					'SH' => 'SH',
					'SZ' => 'SZ',
					'SO' => 'SO',
					'TI' => 'TI',
					'TG' => 'TG',
					'UR' => 'UR',
					'VS' => 'VS',
					'VD' => 'VD',
					'ZG' => 'ZG',
					'ZH' => 'ZH'
				],
				'label' => 'State / Province',
				'row_attr' => ['class' => 'form-group form-focus'],
				'label_attr' => ['class' => 'focus-label'],
				'attr' => ['class' => 'form-control floating']
			])
		 */
			->add('country', ChoiceType::class, [
				'choices' => [
                    $this->translator->trans('Switzerland') => 'CH',
				],
				'label' => $this->translator->trans('Country'),
				'row_attr' => ['class' => 'form-group form-focus'],
				'label_attr' => ['class' => 'focus-label'],
				'attr' => ['class' => 'form-control floating'],
				'preferred_choices' => ['CH'],
			])
			->add('postal_code', TextType::class, [
				'label' => $this->translator->trans('Postal Code'),
				'row_attr' => ['class' => 'form-group form-focus'],
				'label_attr' => ['class' => 'focus-label'],
				'attr' => ['class' => 'form-control floating']
			])
			->add('speciality', ChoiceType::class, [
				'choices'  => [
                    $this->translator->trans('Select')                       =>   null,
                    $this->translator->trans('General Practitioner') 		=>	'General Practitioner',
                    $this->translator->trans('Urologist')			   		=>	'Urologist',
                    $this->translator->trans('Neurologist')		   			=>	'Neurologist',
                    $this->translator->trans('Dentist')			   			=>	'Dentist',
                    $this->translator->trans('Dental Hygienist')				=>  'Dental Hygienist',
                    $this->translator->trans('Orthopedist')		   			=>	'Orthopedist',
                    $this->translator->trans('Cardiologist')		   			=>	'Cardiologist',
                    $this->translator->trans('Psychologist')		   			=>	'Psychologist',
                    $this->translator->trans('Nutritionist-Dietician')		=>	'Nutritionist-Dietician',
                    $this->translator->trans('Alternative/Natural Medicine')	=>	'Alternative/Natural Medicine',
                    $this->translator->trans('Pediatrician')					=>	'Pediatrician',
                    $this->translator->trans('Dermatologist / Aesthetics')	=>	'Dermatologist / Aesthetics',
                    $this->translator->trans('Physiotherapist')				=>	'Physiotherapist',
                    $this->translator->trans('Gynecologist')			    	=>	'Gynecologist',
                    $this->translator->trans('Ophtalmologist')				=>  'Ophtalmologist',
                    $this->translator->trans('Other')						=>	'Other'
				],
				'label' => $this->translator->trans('Specialty'),
				'row_attr' => ['class' => 'form-group form-focus'],
				'label_attr' => ['class' => 'focus-label'],
				'attr' => ['class' => 'form-control floating']
			])
            ->add('email', TextType::class, [
				'label' => $this->translator->trans('Email'),
				'row_attr' => ['class' => 'form-group form-focus'],
				'label_attr' => ['class' => 'focus-label'],
				'attr' => ['class' => 'form-control floating']
			])
            ->add('plainPassword', PasswordType::class, [
				'label' => $this->translator->trans('Create Password'),
				'attr' => ['class' => 'form-control floating'],
				'row_attr' => ['class' => 'form-group form-focus'],
				'label_attr' => ['class' => 'focus-label'],
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Doctor::class,
        ]);
    }
}
