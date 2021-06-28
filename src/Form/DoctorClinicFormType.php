<?php

namespace App\Form;

use App\Entity\Clinic;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class DoctorClinicFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
				'label' => 'Clinic Name',
				'row_attr' => ['class' => 'form-group'],
				'attr' => ['class' => 'form-control'],
				'required' => false
			])
			->add('address', TextType::class, [
				'label' => 'Clinic Address',
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
