<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\WeixinBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class PointActionFormSubmitType.
 */
class PointActionWeixinType extends AbstractType
{
    private $tokenStorage;

    public function __construct($tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $this->tokenStorage->getToken()->getUser();

        $builder->add('weixins', 'entity', [
            'class' => 'MauticPlugin\WeixinBundle\Entity\Weixin',
            'query_builder' => function($er) use($user) {
                return $er->createQueryBuilder('w')->where('w.owner = :owner')->setParameter('owner', $user);
            },
            'expanded'    => false,
            'multiple'    => true,
            'label'       => 'weixin.point.action.weixins',
            'label_attr'  => ['class' => 'control-label'],
            'empty_value' => false,
            'required'    => false,
            'attr'        => [
                'class'   => 'form-control',
            ],
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pointaction_weixin';
    }
}
