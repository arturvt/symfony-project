<?php
/**
 * Created by PhpStorm.
 * User: Artur
 * Date: 10/2/14
 * Time: 7:20 PM
 */

namespace Blog\APIBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('name');

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Blog\APIBundle\Entity\User',
            'csrf_protection'   => false,
        ));
    }

    public function getName()
    {
        return 'User';
    }
}