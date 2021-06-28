<?php

namespace App\Form;

use App\Entity\Doctor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;


class DoctorRegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
			->add('title', TextType::class, [
				/*'choices'  => [
					'Select' 			=> null,
					'Dr.' 				=>	'Dr.',
					'Pr.'			   	=>	'Pr.'
				],*/
				'label' => 'Title',
				'row_attr' => ['class' => 'form-group form-focus'],
				'label_attr' => ['class' => 'focus-label'],
				'attr' => ['class' => 'form-control floating'],
				'required'	=>	false
				
			])
			->add('first_name', TextType::class, [
				'label' => 'First Name',
				'row_attr' => ['class' => 'form-group form-focus'],
				'label_attr' => ['class' => 'focus-label'],
				'attr' => ['class' => 'form-control floating']
			])
			->add('last_name', TextType::class, [
				'label' => 'Last Name',
				'row_attr' => ['class' => 'form-group form-focus'],
				'label_attr' => ['class' => 'focus-label'],
				'attr' => ['class' => 'form-control floating']
			])
			->add('gender', ChoiceType::class, [
				'choices'  => [
					'Select' => null,
					'Male' => 'Male',
					'Female' => 'Female',
				],
				'label' => 'Gender',
				'row_attr' => ['class' => 'form-group form-focus'],
				'label_attr' => ['class' => 'focus-label'],
				'attr' => ['class' => 'form-control floating']
			])
			->add('phone_number', TextType::class, [
				'label' => 'Phone or Mobile Number',
				'row_attr' => ['class' => 'form-group form-focus'],
				'label_attr' => ['class' => 'focus-label'],
				'attr' => ['class' => 'form-control floating']
			])
			->add('address_line_1', TextType::class, [
				'label'      => 'Address Line 1',
				'row_attr'   => ['class' => 'form-group form-focus'],
				'label_attr' => ['class' => 'focus-label'],
				'attr'       => ['class' => 'form-control floating'],
				'required'   => false
			])
			->add('address_line_2', TextType::class, [
				'label' => 'Address Line 2',
				'row_attr' => ['class' => 'form-group form-focus'],
				'label_attr' => ['class' => 'focus-label'],
				'attr' => ['class' => 'form-control floating'],
				'required'=>false
			])
			->add('city', TextType::class, [
				'label' => 'City',
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
					'Switzerland' => 'CH',
				],
				'label' => 'Country',
				'row_attr' => ['class' => 'form-group form-focus'],
				'label_attr' => ['class' => 'focus-label'],
				'attr' => ['class' => 'form-control floating'],
				'preferred_choices' => ['CH'],
			])
			->add('postal_code', TextType::class, [
				'label' => 'Postal Code',
				'row_attr' => ['class' => 'form-group form-focus'],
				'label_attr' => ['class' => 'focus-label'],
				'attr' => ['class' => 'form-control floating']
			])
			->add('speciality', ChoiceType::class, [
				'choices'  => [
					'Select' => null,
					'General Practitioner' 			=>	'General Practitioner',
					'Urologist'			   			=>	'Urologist',
					'Neurologist'		   			=>	'Neurologist',
					'Dentist'			   			=>	'Dentist',
					'Dental Hygienist'				=>  'Dental Hygienist',
					'Orthopedist'		   			=>	'Orthopedist',
					'Cardiologist'		   			=>	'Cardiologist',
					'Psychologist'		   			=>	'Psychologist',
					'Nutritionist-Dietician'		=>	'Nutritionist-Dietician',
					'Alternative/Natural Medicine'	=>	'Alternative/Natural Medicine',
					'Pediatrician'					=>	'Pediatrician',
					'Dermatologist / Aesthetics'	=>	'Dermatologist / Aesthetics',
					'Physiotherapist'				=>	'Physiotherapist',
					'Gynecologist'					=>	'Gynecologist',
					'Ophtalmologist'				=>  'Ophtalmologist',
					'Other'							=>	'Other'
				],
				'label' => 'Specialty',
				'row_attr' => ['class' => 'form-group form-focus'],
				'label_attr' => ['class' => 'focus-label'],
				'attr' => ['class' => 'form-control floating']
			])
            ->add('email', TextType::class, [
				'label' => 'Email',
				'row_attr' => ['class' => 'form-group form-focus'],
				'label_attr' => ['class' => 'focus-label'],
				'attr' => ['class' => 'form-control floating']
			])
            ->add('plainPassword', PasswordType::class, [
				'label' => 'Create Password',
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
