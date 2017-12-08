<?php
declare(strict_types=1);

namespace BOF;

use BOF\DependencyInjection\CompilerPass\ReportPrinterPass;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\Application as ConsoleApplication;
use Symfony\Component\Console\DependencyInjection\AddConsoleCommandPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Yaml\Yaml;

/**
 * Class Application
 * @package BOF
 */
class Application extends ConsoleApplication
{
    /**
     * @var ContainerBuilder
     */
    protected $container;

    public function __construct(string $name = 'app', string $version = '1')
    {
        $this->container = new ContainerBuilder();
        $loader = new YamlFileLoader($this->container, new FileLocator(__DIR__.'/../app'));
        $loader->load('services.yml');
        $loader->load('repository.yml');


        //Collect printer services
        $this->container->addCompilerPass(new ReportPrinterPass());
        //Collect command services
        $this->container->addCompilerPass(new AddConsoleCommandPass());
        $this->initDoctrine();
        $this->container->compile();

        // Initiate app
        parent::__construct($name, $version);

        // Add configured commands
        foreach ($this->container->getParameter('console.command.ids') as $commandId) {
            $this->add($this->container->get($commandId));
        }
    }

    protected function initDoctrine(): void
    {
        $config = Yaml::parseFile(__DIR__.'/../app/doctrine.yml');
        $setup = Setup::createAnnotationMetadataConfiguration($config['entity_path'], $config['dev_mode'], null, null ,false);
        $entityManager = EntityManager::create($config['dbal'], $setup);
        $this->container->set('orm.entity_manager', $entityManager);
    }

    /**
     * @return ContainerBuilder
     */
    public function getContainer()
    {
        return $this->container;
    }
}