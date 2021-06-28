<?php

namespace App\Form;

use App\Entity\Doctor;
use App\Entity\Clinic;
use App\Form\DoctorClinicFormType;
use App\Form\DoctorProfileRegistrationFormType;
use App\Form\DoctorProfileMembershipsFormType;
use App\Form\DoctorProfileExperienceFormType;
use App\Form\DoctorProfileEducationFormType;
use App\Form\DoctorProfileAwardsFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class DoctorProfileSettingsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$languages_json = file_get_contents('https://www.docbooking.ch/data/languages.json');
		$languages= json_decode($languages_json);
		$languages_choose = [];
		$languages_choose[''] = 'Choose a Language...';
		foreach($languages as $lang){
			$languages_choose[$lang->name] = $lang->name;
		}
        $builder
			->add('picture_profile', FileType::class, [
                'label' => 'Brochure (PDF file)',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

				'data_class' => null,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
							'image/jpeg', 
							'image/png', 
							'image/gif', 
							'image/jpg'
                        ],
                        'mimeTypesMessage' => 'Please upload a valid Image',
                    ])
                ],
            ])
			->add('title', TextType::class, [
				'required' => false,
				'label' => 'Title',
				'row_attr' => ['class' => 'form-group'],
				'attr' => ['class' => 'form-control']
			])
			->add('email', TextType::class, [
				'label' => 'Email',
				'row_attr' => ['class' => 'form-group'],
				'attr' => ['class' => 'form-control']
			])
			->add('first_name', TextType::class, [
				'label' => 'First Name',
				'row_attr' => ['class' => 'form-group'],
				'attr' => ['class' => 'form-control']
			])
			->add('last_name', TextType::class, [
				'label' => 'Last Name',
				'row_attr' => ['class' => 'form-group'],
				'attr' => ['class' => 'form-control']
			])
			->add('phone_number', TelType::class, [
				'label' => 'Phone Number',
				'row_attr' => ['class' => 'form-group'],
				'attr' => ['class' => 'form-control']
			])
			->add('gender', ChoiceType::class, [
				'choices'  => [
					'Select' => null,
					'Male' => 'Male',
					'Female' => 'Female',
				],
				'label' => 'Gender',
				'row_attr' => ['class' => 'form-group'],
				'attr' => ['class' => 'form-control']
			])
			->add('date_birth', DateType::class, [
				'label' => 'Date of Birth',
				'row_attr' => ['class' => 'form-group form-focus'],
				'attr' => ['class' => 'form-control'],
				'format' => 'dd/MM/yyyy',
				'widget' => 'single_text',
				'html5' => false
			])
			->add('about_me', TextareaType::class, [
				'label' => 'Biography',
				'row_attr' => ['class' => 'form-group mb-0'],
				'attr' => ['class' => 'form-control', 'rows'	=>	"5"],
				'required'=> false
			])
			->add('speciality', ChoiceType::class, [
				'choices'  => [
					'Select' => null,
					'General Practitioner' 			=>	'General Practitioner',
					'Urologist'			   			=>	'Urologist',
					'Neurologist'		   			=>	'Neurologist',
					'Dentist'			   			=>	'Dentist',
					'Dental Hygienist'				=>	'Dental Hygienist',
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
				'label' => 'Medical Specialty',
				'row_attr' => ['class' => 'form-group'],
				'attr' => ['class' => 'form-control']
			])
			->add('services', TextType::class, [
				'label' => 'Services',
				'row_attr' => ['class' => 'form-group'],
				'attr' => ['class' => 'form-control']
			])
			->add('specialization', TextType::class, [
				'label' => 'Specialization',
				'row_attr' => ['class' => 'form-group'],
				'attr' => ['class' => 'form-control']
			])
			->add('address_line_1', TextType::class, [
				'label' => 'Address Line 1',
				'row_attr' => ['class' => 'form-group'],
				'attr' => ['class' => 'form-control'],
				'required'	=>	false
			])
			->add('address_line_2', TextType::class, [
				'label' => 'Address Line 2',
				'row_attr' => ['class' => 'form-group'],
				'attr' => ['class' => 'form-control'],
				'required'=>false
			])
			->add('city', TextType::class, [
				'label' => 'City',
				'row_attr' => ['class' => 'form-group'],
				'attr' => ['class' => 'form-control']
			])
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
					'OB' => 'OB',
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
				'row_attr' => ['class' => 'form-group'],
				'attr' => ['class' => 'form-control']
			])
			->add('country', ChoiceType::class, [
				'choices' => [
					'Switzerland' => 'CH',
				],
				'label' => 'Country',
				'row_attr' => ['class' => 'form-group'],
				'attr' => ['class' => 'form-control'],
				'preferred_choices' => ['CH'],
			])
			->add('postal_code', TextType::class, [
				'label' => 'Postal Code',
				'row_attr' => ['class' => 'form-group'],
				'attr' => ['class' => 'form-control']
			])
			->add('receiving_patient_info', ChoiceType::class, [
				'choices' => [
					'Always' => 'always',
					'If Patient Wishes' => 'if_patient_wishes',
					'Never' => 'never',
				],
				'choice_attr' => function($choice, $key, $value) {
					// adds a class like attending_yes, attending_no, etc
					return ['class' => 'custom-control-input'];
				},
				'expanded' => true,
				'label'=>null,
				'row_attr' => ['id' => 'medoctor_select'],
				'data' => 'if_patient_wishes'
			])
			->add('price_type', ChoiceType::class, [
				'choices' => [
					'Custom Price (per visit)' => 'custom_price'
				],
				'choice_attr' => function($choice, $key, $value) {
					// adds a class like attending_yes, attending_no, etc
					return ['class' => 'custom-control-input'];
				},
				'expanded' => true,
				'label'=>null,
				'row_attr' => ['id' => 'medoctor_select']
			])
			->add('price_custom_value', MoneyType::class, [
				'scale' => 2,
				'currency' => null,
				'attr' => [
					'min' => '0.00',
					'max' => '1000.00',
					'step' => '0.01'
				],
				'row_attr' => ['class' => 'col-md-4'],
				'attr' => ['class' => 'form-control'],
				'help' => 'Custom price you can add',
			])
			->add('spoken_languages', ChoiceType::class, [
				'choices' => [
					'German' 	=> 'German',
					'French' 	=> 'French',
					'Italian' 	=> 'Italian',
					'English' 	=> 'English',
					'Spanish'	=> 'Spanish',
					'Russian' 	=> 'Russian',
					'Arabic' 	=> 'Arabic',
					'Others' 	=> 'Other'
				],
				'choice_attr' => function($choice, $key, $value) {
					// adds a class like attending_yes, attending_no, etc
					return ['class' => 'custom-control-input'];
				},
				'expanded' => true,
				'multiple'  => true,
				'label'=>null,
				'row_attr' => ['id' => 'medoctor_select']
			])
			->add('lang_other', ChoiceType::class, [
				'choices'  => $languages_choose,
				'row_attr' => ['class' => 'form-group'],
				'label'=>false,
				'attr' => ['class' => 'form-control']
			])
			->add('idClinic', CollectionType::class, [
				'entry_type'   => DoctorClinicFormType::class,
				'entry_options' => ['label' => false],
				'allow_add' => true,
			])
			->add('registrations', CollectionType::class, [
				'entry_type'   => DoctorProfileRegistrationFormType::class,
				'entry_options' => ['label' => false],
				'allow_add' => true,
			])
			->add('memberships', CollectionType::class, [
				'entry_type'   => DoctorProfileMembershipsFormType::class,
				'entry_options' => ['label' => false],
				'allow_add' => true,
			])
			->add('experience', CollectionType::class, [
				'entry_type'   => DoctorProfileExperienceFormType::class,
				'entry_options' => ['label' => false],
				'allow_add' => true,
			])
			->add('education', CollectionType::class, [
				'entry_type'   => DoctorProfileEducationFormType::class,
				'entry_options' => ['label' => false],
				'allow_add' => true,
			])
			->add('awards', CollectionType::class, [
				'entry_type'   => DoctorProfileAwardsFormType::class,
				'entry_options' => ['label' => false],
				'allow_add' => true,
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
