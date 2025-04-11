<?php

namespace App\Form;

use App\Entity\Clinic;
use App\Service\Translator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DoctorClinicFormType extends AbstractType
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
            ->add('name', TextType::class, [
				'label' => $this->translator->trans('Clinic Name'),
				'row_attr' => ['class' => 'form-group'],
				'attr' => ['class' => 'form-control'],
				'required' => false
			])
			->add('address', TextType::class, [
				'label' => $this->translator->trans('Clinic Address'),
				'row_attr' => ['class' => 'form-group'],
				'attr' => ['class' => 'form-control'],
				'required' => false
			])
			->add('images', HiddenType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Clinic::class,
        ]);
    }
}
