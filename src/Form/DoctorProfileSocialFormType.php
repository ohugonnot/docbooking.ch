<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DoctorProfileSocialFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('website_url', TextType::class, [
				'label' => 'Website URL',
				'row_attr' => ['class' => 'form-group'],
				'attr' => ['class' => 'form-control'],
				'required'	=> false
			])
			->add('facebook_url', TextType::class, [
				'label' => 'Facebook URL',
				'row_attr' => ['class' => 'form-group'],
				'attr' => ['class' => 'form-control'],
				'required'	=> false
			])
			->add('twitter_url', TextType::class, [
				'label' => 'Twitter URL',
				'row_attr' => ['class' => 'form-group'],
				'attr' => ['class' => 'form-control'],
				'required'	=> false
			])
			->add('instagram_url', TextType::class, [
				'label' => 'Instagram URL',
				'row_attr' => ['class' => 'form-group'],
				'attr' => ['class' => 'form-control'],
				'required'	=> false
			])
			->add('pinterest_url', TextType::class, [
				'label' => 'Pinterest URL',
				'row_attr' => ['class' => 'form-group'],
				'attr' => ['class' => 'form-control'],
				'required'	=> false
			])
			->add('linkedin_url', TextType::class, [
				'label' => 'Linkedin URL',
				'row_attr' => ['class' => 'form-group'],
				'attr' => ['class' => 'form-control'],
				'required'	=> false
			])
			->add('youtube_url', TextType::class, [
				'label' => 'Youtube URL',
				'row_attr' => ['class' => 'form-group'],
				'attr' => ['class' => 'form-control'],
				'required'	=> false
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
