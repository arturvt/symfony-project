<?php
/**
 * Created by PhpStorm.
 * User: Artur
 * Date: 9/30/14
 * Time: 11:15 AM
 */

namespace Blog\BaseCRUDBundle\Tests\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class FakeEntityType
 *
 * @package Blog\BaseCRUDBundle\Tests\Form
 */
class FakeEntityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('firstName');
        $builder->add('lastName');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Blog\BaseCRUDBundle\Tests\Entity\FakeEntity',
            'csrf_protection'   => false,
        ));
    }

    public function getName()
    {
        return 'FakeEntity';
    }
}