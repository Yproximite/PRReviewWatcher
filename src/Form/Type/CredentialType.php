<?php

namespace PRReviewWatcher\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CredentialType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nameCred', 'text')
            ->add('token', 'text');
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'credential';
    }
}
