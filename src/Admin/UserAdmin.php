<?php
// src/Admin/CategoryAdmin.php

namespace App\Admin;

use App\Entity\EmailSubscription;
use App\Entity\Headquarter;
use FOS\UserBundle\Model\UserManagerInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\UserBundle\Form\Type\SecurityRolesType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\LocaleType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Sonata\AdminBundle\Form\Type\AdminType;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\AdminBundle\Form\Type\CollectionType;

final class UserAdmin extends AbstractAdmin
{

    /**
     * @var UserManagerInterface
     */
    protected $userManager;
    
    protected $baseRouteName = 'admin_sonata_user_user'; 
    protected $baseRoutePattern = 'admin_sonata_user_user';

        /**
     * {@inheritdoc}
     */
    public function getFormBuilder()
    {
        $this->formOptions['data_class'] = $this->getClass();

        $options = $this->formOptions;
        $options['validation_groups'] = (!$this->getSubject() || null === $this->getSubject()->getId()) ? 'Registration' : 'Profile';

        $formBuilder = $this->getFormContractor()->getFormBuilder($this->getUniqid(), $options);

        $this->defineFormBuilder($formBuilder);

        return $formBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function getExportFields()
    {
        // avoid security field to be exported
        return array_filter(parent::getExportFields(), static function ($v) {
            return !\in_array($v, ['password', 'salt'], true);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function preUpdate($user): void
    {
        $this->getUserManager()->updateCanonicalFields($user);
        $this->getUserManager()->updatePassword($user);
    }

    /**
     * {@inheritdoc}
     */
    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            ->with('General')
                ->add('username')
                ->add('email')
            ->end()
            ->with('Profile')
                ->add('firstname')
                ->add('lastname')
                ->add('locale')
                
            ->end()
            ->with('Subscription')
            ->add('emailsubscription')
            ->end()
        ;
    }

    
    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper): void
    {
    // define group zoning
        $formMapper
            ->tab('User')
                ->with('Profile', ['class' => 'col-md-4'])->end()
                ->with('General', ['class' => 'col-md-4'])->end()
                ->with('Subscription',['class' => 'col-md-4'])->end()
            ->end()
            ->tab('Security')
                ->with('Status', ['class' => 'col-md-6'])->end()
                ->with('Roles', ['class' => 'col-md-6'])->end()
            ->end()
        ;

        $now = new \DateTime();

        $formMapper
            ->tab('User')
                ->with('General')
                    ->add('username')
                    ->add('email')
                    ->add('plainPassword', TextType::class, [
                        'required' => (!$this->getSubject() || null === $this->getSubject()->getId()),
                    ])
                ->end()
                ->with('Profile')
                    ->add('firstname', null, ['required' => false])
                    ->add('lastname', null, ['required' => false])
                    ->add('locale', LocaleType::class, ['required' => false])
                ->end()
                ->with('Subscription')
            ->add('emailSubscription', AdminType::class,
                  [],
                  ['admin_code' => 'app.admin.emailsubscription'
                        ])
                    ->end()
            ->end()
            ->tab('Security')
                ->with('Status')
                    ->add('enabled', null, ['required' => false])
                ->end()
                ->with('Roles')
                    ->add('realRoles', SecurityRolesType::class, [
                        'choices'  => ["User"=> 'ROLE_USER',
                                       'Editor'=>'ROLE_EDITOR',
                                       'Admin'=>'ROLE_ADMIN'] ,
                        'label' => 'form.label_roles',
                        'expanded' => true,
                        'multiple' => true,
                        'required' => false,
                    ])
                ->end()
            ->end()
        ;
    }
    
    

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('firstname');
        $datagridMapper->add('lastname');
        $datagridMapper->add('email');
        $datagridMapper->add('enabled');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('firstname');
        $listMapper->addIdentifier('lastname');
        $listMapper->addIdentifier('email');
        $listMapper->addIdentifier('enabled');
    }

    /**
     * @param UserManagerInterface $userManager
     */
    public function setUserManager(UserManagerInterface $userManager): void
    {
        $this->userManager = $userManager;
    }

    /**
     * @return UserManagerInterface
     */
    public function getUserManager()
    {
        return $this->userManager;
    }
}
