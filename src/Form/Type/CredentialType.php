<?php

namespace PRReviewWatcher\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CredentialType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nameCred', 'text')
            ->add('token', 'text', array(
                'mapped' => $options['mapped'],
                'disabled' => $options['disable']
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'credential';
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(['mapped']);
        $resolver->setRequired(['disable']);
    }
}
