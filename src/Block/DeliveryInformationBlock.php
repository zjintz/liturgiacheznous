<?php
namespace App\Block;

use App\Entity\TextsDelivery;
use Doctrine\ORM\EntityManagerInterface;
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
class DeliveryInformationBlock extends AbstractAdminBlockService
{
    private $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager, $name, EngineInterface $templating)
    {
        $this->entityManager = $entityManager;
        parent::__construct($name, $templating);
    }

    public function configureSettings(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'url' => false,
            'title' => 'Delivery Information',
            'template' => 'block/block_delivery_information.html.twig',
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

    private function getLastDelivery($type)
    {
        $query = $this->entityManager->createQuery(
            'SELECT p
            FROM App\Entity\TextsDelivery p
            WHERE p.type = :type
            ORDER BY p.sendDate DESC'
        )->setParameter('type', $type);
        // returns an array of Product objects
        $result = $query->getResult();
        if (empty($result)) {
            return "--:--";
        }
        return $result[0]->getSendDate()->format('Y-m-d H:i');
    }
    
    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        $settings = $blockContext->getSettings();
        $lastDailyDelivery = $this->getLastDelivery("daily") ;
        $tomorrow = new \DateTime(date("Y-m-d", strtotime("+1 day")));
        $nextDailyDelivery = $tomorrow->format('Y-m-d H:i');
        $lastWeeklyDelivery = $this->getLastDelivery("weekly");
        $newDate = new \DateTime(date("Y-m-d", strtotime("next Sunday")));
        $nextWeeklyDelivery = $newDate->format('Y-m-d H:i');
        $lastBiweeklyDelivery =$this->getLastDelivery("biweekly") ;
        $nextBiweeklyDelivery = $newDate->format('Y-m-d H:i');
        if ($lastBiweeklyDelivery !== "--:--") {
            $lastSunday = new \DateTime('last Sunday');
            $lastBiweeklyDate =  new \DateTime($lastBiweeklyDelivery);
            if ($lastBiweeklyDate >= $lastSunday){
                $next = strtotime("next Sunday", strtotime("next Sunday"));
                $nextD = new \DateTime(date("Y-m-d",$next));
                $nextBiweeklyDelivery = $nextD->format('Y-m-d H:i');
            }
        }
        
        return $this->renderResponse($blockContext->getTemplate(), [
            'last_daily' => $lastDailyDelivery,
            'next_daily' => $nextDailyDelivery,
            'last_weekly' => $lastWeeklyDelivery,
            'next_weekly' => $nextWeeklyDelivery,
            'last_biweekly' => $lastBiweeklyDelivery,
            'next_biweekly' => $nextBiweeklyDelivery,
            'block'     => $blockContext->getBlock(),
            'settings'  => $settings
        ], $response);
    }
}
