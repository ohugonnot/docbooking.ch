<?php

namespace App\Form;

use App\Entity\Registrations;
use App\Service\Translator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DoctorProfileRegistrationFormType extends AbstractType
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
            ->add('registrations', TextType::class, [
				'label' => $this->translator->trans('Registrations'),
				'row_attr' => ['class' => 'form-group'],
				'attr' => ['class' => 'form-control']
			])
			->add('year', TextType::class, [
				'label' => $this->translator->trans('Year'),
				'row_attr' => ['class' => 'form-group'],
				'attr' => ['class' => 'form-control']
			])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Registrations::class,
        ]);
    }
}
