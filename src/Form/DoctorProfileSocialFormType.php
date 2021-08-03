<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class DoctorProfileSocialFormType extends AbstractType
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
            ->add('website_url', TextType::class, [
				'label' => $this->translator->trans('Website URL'),
				'row_attr' => ['class' => 'form-group'],
				'attr' => ['class' => 'form-control'],
				'required'	=> false
			])
			->add('facebook_url', TextType::class, [
				'label' => $this->translator->trans('Facebook URL'),
				'row_attr' => ['class' => 'form-group'],
				'attr' => ['class' => 'form-control'],
				'required'	=> false
			])
			->add('twitter_url', TextType::class, [
				'label' => $this->translator->trans('Twitter URL'),
				'row_attr' => ['class' => 'form-group'],
				'attr' => ['class' => 'form-control'],
				'required'	=> false
			])
			->add('instagram_url', TextType::class, [
				'label' => $this->translator->trans('Instagram URL'),
				'row_attr' => ['class' => 'form-group'],
				'attr' => ['class' => 'form-control'],
				'required'	=> false
			])
			->add('pinterest_url', TextType::class, [
				'label' => $this->translator->trans('Pinterest URL'),
				'row_attr' => ['class' => 'form-group'],
				'attr' => ['class' => 'form-control'],
				'required'	=> false
			])
			->add('linkedin_url', TextType::class, [
				'label' => $this->translator->trans('Linkedin URL'),
				'row_attr' => ['class' => 'form-group'],
				'attr' => ['class' => 'form-control'],
				'required'	=> false
			])
			->add('youtube_url', TextType::class, [
				'label' => $this->translator->trans('Youtube URL'),
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
