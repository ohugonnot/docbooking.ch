<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\CallbackTransformer;

class PatientProfileSettingsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('picture_profile', FileType::class, [
                'label' => 'Brochure (PDF file)',

                // unmapped means that this field is not associated to any entity property
                'mapped' => true,

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
			->add('date_birth', DateType::class, [
				'label' => 'Date of Birth',
				'row_attr' => ['class' => 'form-group form-focus'],
				'attr' => ['class' => 'form-control'],
				'format' => 'dd/MM/yyyy',
				'widget' => 'single_text',
				'html5' => false
			])
			->add('blood_group', ChoiceType::class, [
				'choices'  => [
					'Select' => '',
					'A-' => 'A-',
					'A+' => 'A+',
					'B-' => 'B-',
					'B+' => 'B+',
					'AB' => 'AB',
					'O-' => 'O-',
					'O+' => 'O+',
				],
				'label' => 'Blood Group',
				'row_attr' => ['class' => 'form-group'],
				'attr' => ['class' => 'form-control']
			])
			->add('email', TextType::class, [
				'label' => 'Email',
				'row_attr' => ['class' => 'form-group'],
				'attr' => ['class' => 'form-control']
			])
			->add('phone_number', TelType::class, [
				'label' => 'Mobile',
				'row_attr' => ['class' => 'form-group'],
				'attr' => ['class' => 'form-control']
			])
			->add('address', TextType::class, [
				'label' => 'Address',
				'row_attr' => ['class' => 'form-group'],
				'attr' => ['class' => 'form-control']
			])
			->add('city', TextType::class, [
				'label' => 'City',
				'row_attr' => ['class' => 'form-group'],
				'attr' => ['class' => 'form-control']
			])
			->add('state', TextType::class, [
				'label' => 'State',
				'row_attr' => ['class' => 'form-group'],
				'attr' => ['class' => 'form-control']
			])
			->add('postal_code', TextType::class, [
				'label' => 'Zip Code',
				'row_attr' => ['class' => 'form-group'],
				'attr' => ['class' => 'form-control']
			])
			->add('country', CountryType::class, [
				'label' => 'Country',
				'row_attr' => ['class' => 'form-group'],
				'attr' => ['class' => 'form-control']
			])
			->add('insurance', ChoiceType::class, [
				'choices'  => [
					'Select' 		=> '',
					'Groupe Mutuel'	=>	'Groupe Mutuel',
					'CSS'			=>	'CSS',
					'Helsana'		=>	'Helsana',
					'Concordia'		=>	'Concordia',
					'Swica'			=>	'Swica',
					'Visana'		=>	'Visana',
					'Assura'		=>	'Assura',
					'Sanitas'		=>	'Sanitas',
					'Intras'		=>	'Intras',
					'KPT-CPT'		=>	'KPT-CPT',
					'Wincare'		=>	'Wincare',
					'Atupri'		=>	'Atupri',
					'ÖKK'			=>	'ÖKK',
					'Sympany'		=>	'Sympany',
					'Other'			=>	'Other'
				],
				'label' => 'Insurance',
				'row_attr' => ['class' => 'form-group'],
				'attr' => ['class' => 'form-control']
			])
			->add('insurance_num', TextType::class, [
				'label' => 'Insurance Number',
				'row_attr' => ['class' => 'form-group'],
				'attr' => ['class' => 'form-control']
			])
        ;

		/*$builder->get('picture_profile')
            ->addModelTransformer(new CallbackTransformer(
                function ($pictureProfile) {
                    // transform the array to a string
                    return $pictureProfile;
                },
                function ($pictureProfile) {
                    if($pictureProfile == null){
						$pictureProfile = '';
					}
					return $pictureProfile;
                }
            ))
        ;*/

		/*$builder->get('date_birth')
            ->addModelTransformer(new CallbackTransformer(
                function ($dateBirth) {
                    // transform the array to a string
                    return $dateBirth;
                },
                function ($dateBirth) {
                    if($dateBirth == null){
						$dateBirth = date('dd/MM/yyyy', '00/00/0000');
					}
					return $dateBirth;
                }
            ))
        ;*/
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
