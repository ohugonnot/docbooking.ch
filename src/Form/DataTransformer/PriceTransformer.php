<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class PriceTransformer implements DataTransformerInterface
{
    /**
     * Transforms cent to dollar amount.
     *
     * @param  int|null $priceInCent
     * @return double
     */
    public function transform($priceInCent)
    {
        if (null === $priceInCent) {
            return;
        }

        $priceInDollar = number_format(($priceInCent /100), 2, '.', ' ');

        return $priceInDollar;
    }

    /**
     * Transforms dollar to cent amount.
     *
     * @param  double|null $priceInDollar
     * @return int
     */
    public function reverseTransform($priceInDollar)
    {
        if (null === $priceInDollar) {
            return;
        }

        $priceInCent = (int)($priceInDollar * 100);

        return $priceInCent;
    }
}