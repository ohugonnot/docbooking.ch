<?php

namespace App\Form;

use App\Entity\Education;
use App\Service\Translator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DoctorProfileEducationFormType extends AbstractType
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
           ->add('degree', TextType::class, [
				'label' => $this->translator->trans('Degree'),
				'row_attr' => ['class' => 'form-group'],
				'attr' => ['class' => 'form-control']
			])
			->add('college_institute', TextType::class, [
				'label' => $this->translator->trans('College/Institute'),
				'row_attr' => ['class' => 'form-group'],
				'attr' => ['class' => 'form-control']
			])
			->add('year_completion', TextType::class, [
				'label' => $this->translator->trans('Year of Completion'),
				'row_attr' => ['class' => 'form-group'],
				'attr' => ['class' => 'form-control']
			])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Education::class,
        ]);
    }
}
