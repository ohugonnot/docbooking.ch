<?php

namespace App\Form;

use App\Entity\Awards;
use App\Service\Translator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DoctorProfileAwardsFormType extends AbstractType
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
            ->add('awards', TextType::class, [
				'label' => $this->translator->trans('Awards'),
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
            'data_class' => Awards::class,
        ]);
    }
}
