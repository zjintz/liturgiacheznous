<?php

namespace App\Block;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\Service\AbstractAdminBlockService;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\CoreBundle\Validator\ErrorElement;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Templating\EngineInterface;

/**
 * A block to display the subscription of a user in the dashboard.
 *
 *
 */
class EmailSubscriptionBlock extends AbstractAdminBlockService
{
    private $security;

    public function __construct(Security $security, $name, EngineInterface $templating)
    {
        $this->security = $security;
        parent::__construct($name, $templating);
    }

    public function configureSettings(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'url' => false,
            'title' => 'Email Subscription',
            'template' => 'block/block_email_subscription.html.twig',
        ]);
    }

    public function buildEditForm(FormMapper $formMapper, BlockInterface $block)
    {
        $formMapper
            ->add('settings', 'sonata_type_immutable_array', [
                'keys' => [
                    ['url', 'url', ['required' => false]],
                    ['title', 'text', ['required' => false]],
                ]
            ])
            ;
    }

    public function validateBlock(ErrorElement $errorElement, BlockInterface $block)
    {
        $errorElement
            ->with('settings.url')
            ->assertNotNull([])
            ->assertNotBlank()
            ->end()
            ->with('settings.title')
            ->assertNotNull([])
            ->assertNotBlank()
            ->assertMaxLength(['limit' => 50])
            ->end()
            ;
    }

    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        // merge settings
        $settings = $blockContext->getSettings();
        $user = $this->security->getUser();
        $userId = $user->getId();
        $subscription = $user->getEmailSubscription();
        
        return $this->renderResponse($blockContext->getTemplate(), [
            'subscription'     => $subscription,
            'user_id'     => $userId,
            'block'     => $blockContext->getBlock(),
            'settings'  => $settings
        ], $response);
    }
}
