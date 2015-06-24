<?php

namespace PRReviewWatcher\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProjectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text')
            ->add('branch', 'text')
            ->add('credential', 'choice', array(
                'choices' => $options['credentialChoices'],
            ))
            ->add('comment', 'textarea')
            ->add('alive', 'checkbox', array(
                'required' => false,
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'project';
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(['credentialChoices']);

        $resolver->setDefaults(['credentialChoices' => array()]);
    }
}
