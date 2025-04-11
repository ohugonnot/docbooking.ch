<?php

namespace App\Form;

use App\Service\Translator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DoctorSortFormType extends AbstractType
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
			->add('gender', ChoiceType::class, [
				'choices'  => [
					'Select' => null,
					'21' => 'Shortest Distance',
					'22' => 'Lowest Price',
				],
				'label' => $this->translator->trans('Gender'),
				'row_attr' => ['class' => 'form-group'],
				'attr' => ['class' => 'form-control']
			])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
