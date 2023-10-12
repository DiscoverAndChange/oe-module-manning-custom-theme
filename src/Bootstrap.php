<?php

namespace DiscoverAndChange\Modules\CustomManningTheme;

/**
 * Note the below use statements are importing classes from the OpenEMR core codebase
 */

use OpenEMR\Common\Logging\SystemLogger;
use OpenEMR\Common\Twig\TwigContainer;
use OpenEMR\Core\Kernel;
use OpenEMR\Events\Core\StyleFilterEvent;
use OpenEMR\Events\Core\TemplatePageEvent;
use OpenEMR\Events\Core\TwigEnvironmentEvent;
use OpenEMR\Events\Globals\GlobalsInitializedEvent;
use OpenEMR\Events\Main\Tabs\RenderEvent;
use OpenEMR\Events\RestApiExtend\RestApiResourceServiceEvent;
use OpenEMR\Events\RestApiExtend\RestApiScopeEvent;
use OpenEMR\Services\Globals\GlobalSetting;
use OpenEMR\Menu\MenuEvent;
use OpenEMR\Events\RestApiExtend\RestApiCreateEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Twig\Error\LoaderError;
use Twig\Loader\FilesystemLoader;
// we import our own classes here.. although this use statement is unnecessary it forces the autoloader to be tested.
use OpenEMR\Modules\CustomModuleSkeleton\TaskRestController;

class Bootstrap
{
    const MODULE_INSTALLATION_PATH = "/interface/modules/custom_modules/";
    const MODULE_NAME = "oe-module-custom-theme";
    /**
     * @var EventDispatcherInterface The object responsible for sending and subscribing to events through the OpenEMR system
     */
    private $eventDispatcher;

    /**
     * @var GlobalConfig Holds our module global configuration values that can be used throughout the module.
     */
    private $globalsConfig;

    /**
     * @var string The folder name of the module.  Set dynamically from searching the filesystem.
     */
    private $moduleDirectoryName;

    /**
     * @var \Twig\Environment The twig rendering environment
     */
    private $twig;

    /**
     * @var SystemLogger
     */
    private $logger;

    public function __construct(EventDispatcherInterface $eventDispatcher, ?Kernel $kernel = null)
    {
        global $GLOBALS;

        if (empty($kernel)) {
            $kernel = new Kernel();
        }
//
//        // NOTE: eventually you will be able to pull the twig container directly from the kernel instead of instantiating
//        // it here.
//        $twig = new TwigContainer($this->getTemplatePath(), $kernel);
//        $twigEnv = $twig->getTwig();
//        $this->twig = $twigEnv;

        $this->moduleDirectoryName = basename(dirname(__DIR__));
        $this->eventDispatcher = $eventDispatcher;
        $this->eventDispatcher->addListener(TwigEnvironmentEvent::EVENT_CREATED, [$this, 'addTemplateOverrideLoader']);

        $this->logger = new SystemLogger();
    }

    public function subscribeToEvents()
    {
        $this->eventDispatcher->addListener(StyleFilterEvent::EVENT_NAME, [$this, 'addStylesheet']);
        $this->eventDispatcher->addListener(TemplatePageEvent::class, [$this, 'oauth2TemplatePageOverrides']);
    }

    public function addTemplateOverrideLoader(TwigEnvironmentEvent $event)
    {
        // TODO: @adunsulag figure out why this is getting fired twice.
        $twig = $event->getTwigEnvironment();
        // we make sure we can override our file system directory here.
        $loader = $twig->getLoader();
        if ($loader instanceof FilesystemLoader) {
            $loader->prependPath($this->getTemplatePath());
        }
    }

    private function getTemplatePath()
    {
        return \dirname(__DIR__) . DIRECTORY_SEPARATOR . "templates" . DIRECTORY_SEPARATOR;
    }


    public function oauth2TemplatePageOverrides(TemplatePageEvent $event)
    {
        $template = $event->getPageName();
        if ($template == 'oauth2/authorize/smart-style') {
            $event->setTwigTemplate('manning-custom-theme/api/smart/smart-style_manning.json.twig');
        }
        return $event;
    }

    public function addStylesheet(StyleFilterEvent $event)
    {
        $styles = $event->getStyles();
        $styles[] = $this->getAssetPath() . "/css/manning-theme.css?v=" . urlencode($GLOBALS['v_js_includes']);
        $event->setStyles($styles);
    }

    /**
     * @return GlobalConfig
     */
    public function getGlobalConfig()
    {
        return $this->globalsConfig;
    }

    private function getPublicPath()
    {
        return self::MODULE_INSTALLATION_PATH . ($this->moduleDirectoryName ?? '') . '/public/';
    }

    private function getAssetPath()
    {
        return $this->getPublicPath() . '/assets/';
    }
}
