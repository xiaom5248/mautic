<?php
/**
 * Created by PhpStorm.
 * User: hackc
 * Date: 2017-07-31
 * Time: 15:21
 */

namespace Mautic\SmsBundle\Form\Type;


use Mautic\CoreBundle\Factory\MauticFactory;
use Mautic\CoreBundle\Form\EventListener\CleanFormSubscriber;
use Mautic\CoreBundle\Form\EventListener\FormExitSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SignType extends AbstractType
{
    private $translator;
    private $em;
    private $request;

    /**
     * SignType constructor.
     * @param MauticFactory $factory
     */
    public function __construct(MauticFactory $factory)
    {
        $this->translator   = $factory->getTranslator();
        $this->em           = $factory->getEntityManager();
        $this->request      = $factory->getRequest();
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventSubscriber(new CleanFormSubscriber(['content' => 'html','customHtml' => 'html']));
        $builder->addEventSubscriber(new FormExitSubscriber('sms.sign',$options));

        $builder->add(
            'name',
            'text',
            [
                'label'         =>  'mautic.sign.form.internal.name',
                'label_attr'    =>  ['class'    =>  'control-label'],
                'attr'          =>  ['class'    =>  'form-control'],
            ]
        );

//        $builder->add(
//            'description',
//            'textarea',
//            [
//                'label'     =>  'mautic.sms.form.internal.description',
//                'label_attr'=>  ['class' => 'control-label'],
//                'attr'      =>  ['class' => 'form-control'],
//                'required'  =>  false,
//            ]
//        );



        $builder->add('buttons','form_buttons');

        if(!empty($options['update_select'])) {
            $builder->add(
                'buttons',
                'form_buttons',
                [
                    'apply_text'    =>  false,
                ]
            );
        }

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class'    => 'Mautic\SmsBundle\Entity\Sign',
            ]
        );

        $resolver->setOptional(['update_select']);
    }

    public function getName()
    {
        return 'sign';
    }
}