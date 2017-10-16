<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Mautic\SmsBundle\Form\Type;

use Mautic\CoreBundle\Factory\MauticFactory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class SignListType.
 */
class SignListType extends AbstractType
{
    private $choices = [];

    /**
     * @param MauticFactory $factory
     */
    public function __construct(MauticFactory $factory)
    {
        $choices = $factory->getModel('sign')->getRepository()->getEntities([
            'filter' => [
                'force' => [
                    [
                        'column' => 's.isPublished',
                        'expr'   => 'eq',
                        'value'  => true,
                    ],
                ],
            ],
        ]);

        foreach ($choices as $choice) {
            $this->choices[$choice->getId()] = $choice->getName(true);
        }

        //sort by language
        ksort($this->choices);
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'choices'     => $this->choices,
            'empty_value' => false,
            'expanded'    => false,
            'multiple'    => true,
            'required'    => false,
            'empty_value' => 'mautic.core.form.chooseone',
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sign_list';
    }

    public function getParent()
    {
        return 'choice';
    }
}
