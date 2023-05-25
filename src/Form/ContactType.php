<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('objet', TextType::class, [
                'attr' => ['class' => 'form'],
                'required' => true
            ])
            ->add('date', DateType::class)
            ->add('contenu')
            ->add('mail')
            ->add('nom', TextType::class, [
                'attr' => ['class' => 'form'],
                'label' => 'Saisir votre nom : ',
                'required' => true
            ])
            ->add('prenom', TextType::class, [
                'attr' => ['class' => 'form'],
                'label' => 'Saisir votre prénom : ',
                'required' => true
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Submit',
                'attr' => ['class' => 'btn btn-primary']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
