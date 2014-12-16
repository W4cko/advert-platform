<?php

namespace Kev\PlatformBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AdvertType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',     'text')
            ->add('content',   'textarea')
            ->add('author',    'text')
            //->add('published', 'checkbox', array('required' => false))
            ->add('image', new ImageType())
            ->add('categories', 'entity', array(
                'class'     => 'KevPlatformBundle:Category',
                'property'  => 'name',
                'multiple'  => true
            ) )
            ->add('save', 'submit')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Kev\PlatformBundle\Entity\Advert'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'kev_platformbundle_advert';
    }
}
