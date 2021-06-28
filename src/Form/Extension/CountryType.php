<?php
namespace App\Form\Extension;

use Symfony\Component\Form\Extension\Core\Type\CountryType as BaseCountryType;
use Symfony\Component\Form\ChoiceList\ArrayChoiceList;
use Symfony\Component\Intl\Intl;

class CountryType extends BaseCountryType
{
    /**
     * {@inheritdoc}
     */
    public function loadChoiceList($value = null)
    {
        if (null !== $this->choiceList) {
            return $this->choiceList;
        }

        $countryNames = array_filter(Intl::getRegionBundle()->getCountryNames(), function ($name, $isoCode) {
            return in_array($isoCode, ['US', 'CA', 'RU']);
        });

        return $this->choiceList = new ArrayChoiceList(array_flip($countryNames), $value);
    }
}