<?php
/**
 * Created by PhpStorm.
 * User: hackc
 * Date: 2017-08-09
 * Time: 11:02
 */

namespace Mautic\SmsBundle\Form\Type;


use Mautic\CoreBundle\Factory\MauticFactory;
use Mautic\CoreBundle\Form\DataTransformer\IdToEntityModelTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class SmsTestSendType extends AbstractType
{
    private $translator;
    private $em;
    private $request;

    public function __construct(MauticFactory $factory)
    {
        $this->translator   = $factory->getTranslator();
        $this->em           = $factory->getEntityManager();
        $this->request      = $factory->getRequest();
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'mobile',
            'text',
            [
                'label'      => '手机号',
                'label_attr' => ['class' => 'control-label'],
                'attr'       => ['class' => 'form-control'],
            ]
        );


        $builder->add(
            'buttons',
            'form_buttons',
            [
                'apply_text'    =>  false,
                'save_text'     =>  'mautic.sms.send',
                'save_icon'     =>  'fa fa-send',
            ]
        );
    }
}