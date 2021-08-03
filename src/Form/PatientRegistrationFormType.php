<?php

namespace App\Form;

use App\Entity\Patient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Translation\TranslatorInterface;

class PatientRegistrationFormType extends AbstractType
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
			->add('first_name', TextType::class, [
				'label' => $this->translator->trans('First Name'),
				'row_attr' => ['class' => 'form-group form-focus'],
				'label_attr' => ['class' => 'focus-label'],
				'attr' => ['class' => 'form-control floating', 'lang'	=>	'en']
			])
			->add('last_name', TextType::class, [
				'label' => $this->translator->trans('Last Name'),
				'row_attr' => ['class' => 'form-group form-focus'],
				'label_attr' => ['class' => 'focus-label'],
				'attr' => ['class' => 'form-control floating', 'lang'	=>	'en']
			])
			->add('phone_number', TextType::class, [
				'label' => $this->translator->trans('Phone or Mobile Number'),
				'row_attr' => ['class' => 'form-group form-focus'],
				'label_attr' => ['class' => 'focus-label'],
				'attr' => ['class' => 'form-control floating', 'lang'	=>	'en']
			])
            ->add('email', TextType::class, [
				'label' => $this->translator->trans('Email'),
				'row_attr' => ['class' => 'form-group form-focus'],
				'label_attr' => ['class' => 'focus-label'],
				'attr' => ['class' => 'form-control floating', 'lang'	=>	'en']
			])
            /*->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])*/
            ->add('plainPassword', PasswordType::class, [
				'label' => $this->translator->trans('Create Password'),
				'attr' => ['class' => 'form-control floating', 'lang' => 'en'],
				'row_attr' => ['class' => 'form-group form-focus'],
				'label_attr' => ['class' => 'focus-label'],
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
            'data_class' => Patient::class,
        ]);
    }
}
