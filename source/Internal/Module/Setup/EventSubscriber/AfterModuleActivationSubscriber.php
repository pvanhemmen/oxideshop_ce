<?php declare(strict_types=1);
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Internal\Module\Setup\EventSubscriber;

use OxidEsales\EshopCommunity\Internal\Module\Configuration\Provider\ModuleConfigurationProviderInterface;
use OxidEsales\EshopCommunity\Internal\Module\Setup\Event\AfterModuleActivationEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @internal
 */
class AfterModuleActivationSubscriber implements EventSubscriberInterface
{

    /**
     * @var ModuleConfigurationProviderInterface
     */
    private $moduleConfigurationProvider;

    /**
     * @param ModuleConfigurationProviderInterface $moduleConfigurationProvider
     */
    public function __construct(ModuleConfigurationProviderInterface $moduleConfigurationProvider)
    {
        $this->moduleConfigurationProvider = $moduleConfigurationProvider;
    }

    /**
     * @param AfterModuleActivationEvent $event
     */
    public function executeMetadataOnActivationEvent(AfterModuleActivationEvent $event)
    {
        $moduleConfiguration = $this->moduleConfigurationProvider->getModuleConfiguration(
            $event->getModuleId(),
            $event->getEnvironmentName(),
            $event->getShopId()
        );
        $events = $moduleConfiguration->getSetting('events')->getValue();
        if (is_array($events) && array_key_exists('onActivate', $events)) {
            call_user_func($events['onActivate']);
        }
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents() : array
    {
        return [
            AfterModuleActivationEvent::NAME => 'executeMetadataOnActivationEvent',
        ];
    }
}