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
use Symfony\Contracts\Translation\TranslatorInterface;

class PatientProfileSettingsFormType extends AbstractType
{
    private TranslatorInterface $translator;

    /**
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('picture_profile', FileType::class, [
                'label' => $this->translator->trans('Brochure (PDF file)'),

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
				'label' => $this->translator->trans('Date of Birth'),
				'row_attr' => ['class' => 'form-group form-focus'],
				'attr' => ['class' => 'form-control'],
				'format' => 'dd/MM/yyyy',
				'widget' => 'single_text',
				'html5' => false
			])
			->add('blood_group', ChoiceType::class, [
				'choices'  => [
					'Select' => '',
                    $this->translator->trans('A-') => 'A-',
                    $this->translator->trans('A+') => 'A+',
                    $this->translator->trans('B-') => 'B-',
                    $this->translator->trans('B+') => 'B+',
                    $this->translator->trans('AB') => 'AB',
                    $this->translator->trans('O-') => 'O-',
                    $this->translator->trans('O+') => 'O+',
				],
				'label' => $this->translator->trans('Blood Group'),
				'row_attr' => ['class' => 'form-group'],
				'attr' => ['class' => 'form-control']
			])
			->add('email', TextType::class, [
				'label' => 'Email',
				'row_attr' => ['class' => 'form-group'],
				'attr' => ['class' => 'form-control']
			])
			->add('phone_number', TelType::class, [
				'label' => $this->translator->trans('Mobile'),
				'row_attr' => ['class' => 'form-group'],
				'attr' => ['class' => 'form-control']
			])
			->add('address', TextType::class, [
				'label' => $this->translator->trans('Address'),
				'row_attr' => ['class' => 'form-group'],
				'attr' => ['class' => 'form-control']
			])
			->add('city', TextType::class, [
				'label' => 'City',
				'row_attr' => ['class' => 'form-group'],
				'attr' => ['class' => 'form-control']
			])
			->add('state', TextType::class, [
				'label' => $this->translator->trans('State'),
				'row_attr' => ['class' => 'form-group'],
				'attr' => ['class' => 'form-control']
			])
			->add('postal_code', TextType::class, [
				'label' => $this->translator->trans('Zip Code'),
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
                    $this->translator->trans('Groupe Mutuel')	=>	'Groupe Mutuel',
                    $this->translator->trans('CSS')		    =>	'CSS',
                    $this->translator->trans('Helsana')		=>	'Helsana',
                    $this->translator->trans('Concordia')	=>	'Concordia',
                    $this->translator->trans('Swica')		=>	'Swica',
                    $this->translator->trans('Visana')		=>	'Visana',
                    $this->translator->trans('Assura')		=>	'Assura',
                    $this->translator->trans('Sanitas')		=>	'Sanitas',
                    $this->translator->trans('Intras')		=>	'Intras',
                    $this->translator->trans('KPT-CPT')		=>	'KPT-CPT',
                    $this->translator->trans('Wincare')		=>	'Wincare',
                    $this->translator->trans('Atupri')		=>	'Atupri',
                    $this->translator->trans('ÖKK')			=>	'ÖKK',
                    $this->translator->trans('Sympany')		=>	'Sympany',
                    $this->translator->trans('Other')		=>	'Other'
				],
				'label' => $this->translator->trans('Insurance'),
				'row_attr' => ['class' => 'form-group'],
				'attr' => ['class' => 'form-control']
			])
			->add('insurance_num', TextType::class, [
				'label' => $this->translator->trans('Insurance Number'),
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
