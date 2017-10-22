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

use Mautic\CoreBundle\Form\EventListener\CleanFormSubscriber;
use Mautic\CoreBundle\Form\EventListener\FormExitSubscriber;
use MauticPlugin\WeixinBundle\Entity\Rule;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class RoleType.
 */
class RuleType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('type', 'choice', [
            'label' => 'weixin.rule.type',
            'choices' => Rule::$ruleTypes,
            'label_attr' => ['class' => 'control-label'],
            'attr'       => ['class' => 'form-control'],
            'required'   => false,
        ]);
        $builder->add('keywords', 'text', [
            'label' => 'weixin.rule.keywords',
            'label_attr' => ['class' => 'control-label'],
            'attr'       => ['class' => 'form-control'],
            'required'   => false,
        ]);
        $builder->add('message', MessageType::class, [
            'label' => 'weixin.rule.message',
            'required'   => false,
        ]);

        $builder->get('keywords')
            ->addModelTransformer(new CallbackTransformer(
                function ($keywordsAsArray) {
                    if(null === $keywordsAsArray){
                        return '';
                    }
                    return implode(' ', $keywordsAsArray);
                },
                function ($keywordsAsString) {
                    return explode(' ', $keywordsAsString);
                }
            ));

    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'MauticPlugin\WeixinBundle\Entity\Rule',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'weixin_followed_message';
    }
}
