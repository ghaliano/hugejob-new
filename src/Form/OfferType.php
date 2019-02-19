<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\Offer;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
class OfferType extends AbstractType
{

    private $tokenStorage;

    public function __construct(TokenStorageInterface $token)
    {
        $this->tokenStorage = $token;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('startAt', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('expireAt', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add(
                'company',
                EntityType::class, [
                'class' => Company::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er
                        ->createQueryBuilder('c')
                        ->andWhere('c.user = :user')
                        ->setParameter(
                            'user',
                            $this
                                ->tokenStorage
                                ->getToken()
                                ->getUser()
                        )
                        ->orderBy('c.name', 'ASC')
                    ;
                }
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Offer::class,
        ]);
    }
}
