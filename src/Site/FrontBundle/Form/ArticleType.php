<?php

namespace Site\FrontBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text', array(
                                                    'attr' => array(
                                                        'placeholder' => 'Titre de l\'article',
                                                        'Class'=>'form-control'
                                                        )
                                                ))
                ->add('description', 'textarea', array(
                                                    'attr' => array(
                                                        'Class'=>'form-control '
                                                        )
                                                ))
                ->add('categorie', 'entity',array(
                                        'class' => 'SiteFrontBundle:Categorie',
                                        'property' => 'name',
                                        'attr' => array(
                                                        'Class'=>'form-control'
                                                        )
                                        ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Site\FrontBundle\Entity\Article'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'site_frontbundle_article';
    }


}
