<?php

namespace App\Form;

use App\Entity\Experience;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DoctorProfileExperienceFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('hospital_name', TextType::class, [
				'label' => 'Hospital Name',
				'row_attr' => ['class' => 'form-group'],
				'attr' => ['class' => 'form-control']
			])
			->add('experience_from', DateType::class, [
				'label' => 'From',
				'row_attr' => ['class' => 'form-group'],
				'attr' => ['class' => 'form-control'],
				'format' => 'dd/MM/yyyy',
				'widget' => 'single_text',
				'html5' => false
			])
			->add('experience_to', DateType::class, [
				'label' => 'To',
				'row_attr' => ['class' => 'form-group'],
				'attr' => ['class' => 'form-control'],
				'format' => 'dd/MM/yyyy',
				'widget' => 'single_text',
				'html5' => false
			])
			->add('designation', TextType::class, [
				'label' => 'Designation',
				'row_attr' => ['class' => 'form-group'],
				'attr' => ['class' => 'form-control']
			])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Experience::class,
        ]);
    }
}
