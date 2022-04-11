<?php

namespace App\Service;

use SplFileObject;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class Translator
{
    private TranslatorInterface $translator;
    private KernelInterface $kernel;

    public function __construct(TranslatorInterface $translator, KernelInterface $kernel)
    {

        $this->translator = $translator;
        $this->kernel = $kernel;
    }

    public function trans(?string $id, array $parameters = [], string $domain = null, string $locale = null) : string
    {
        return $this->translator->trans($id,$parameters,$domain,$locale);

        $filesystem = new Filesystem();
        $file_name = $this->kernel->getProjectDir().'/templates/_include/translation.html.twig';
        $file = new SplFileObject($file_name);
        $translations = [];
        while(!$file->eof())
            $translations[] = explode('->',$file->fgets());
        foreach($translations as $translation)
            if(isset($translation[0]) && $translation[0]==$id || strpos('__', $id))
                return $this->translator->trans($id,$parameters,$domain,$locale);

        $filesystem->appendToFile($file_name, $id."->{{ \"".$id."\"|trans }}\n");
        return $this->translator->trans($id,$parameters,$domain,$locale);
    }
}