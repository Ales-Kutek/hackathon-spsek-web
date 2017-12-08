<?php
/**
 * Spustí fixtures.
 */

namespace Commands;

use Nette\Neon\Neon;
use Nette\Utils\Finder;
use Psr\Log\NullLogger;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
use UW\Core\App\Helper\ArrayMapper;

/**
 * RunCommand class
 */
class CreateFixtureCommand extends Command
{
    /**
     * @var \Zenify\DoctrineFixtures\Alice\AliceLoader
     */
    private $alice;

    /**
     * @var \Kdyby\Doctrine\EntityManager $em
     */
    private $em;

    /**
     * @var array
     */
    private $config;

    /**
     * Fixtures constructor.
     * @param \Zenify\DoctrineFixtures\Alice\AliceLoader $alice
     * @param \Kdyby\Doctrine\EntityManager $em
     */
    public function __construct(\Zenify\DoctrineFixtures\Alice\AliceLoader $alice, \Kdyby\Doctrine\EntityManager $em)
    {
        $this->alice = $alice;
        $this->em = $em;
        parent::__construct();
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param array $config
     * @return Fixtures
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
        return $this;
    }

    /**
     * Injectnutí alice a entity manager
     * @param \Zenify\DoctrineFixtures\Alice\AliceLoader $alice
     */
    public function injectAlice(\Zenify\DoctrineFixtures\Alice\AliceLoader $alice, \Kdyby\Doctrine\EntityManager $em)
    {
        $this->alice = $alice;
        $this->em = $em;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('fixtures:run')
            ->setDescription('Apllying fixtures.');
    }

    /**
     * Nahrání složky s yml, neon soubory.
     * soubory se nahrávají postupně, podle závislostí.. nutno takhle ručně řešit
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fixtures = array(
            __DIR__ . DS . ".." . DS . "fixtures" . DS . "User.yml"
        );


        /* načtení všech fixtures najednou */
        $this->alice->load($fixtures);

        echo "DONE\n";
    }

    public function truncateAllTables() {
        $query = $this->em->getConnection()->prepare("SELECT Concat('TRUNCATE TABLE `',TABLE_NAME, '`;') FROM INFORMATION_SCHEMA.TABLES where TABLE_SCHEMA in ('cms_cz');");
        $query->execute();

        $result = $query->fetchAll(\PDO::FETCH_NUM);

        $query->closeCursor();

        $query = NULL;

        $sql = "";

        foreach($result as $key => $value) {
            $sql .= $value[0];
        }

        $query = $this->em->getConnection()->exec("SET FOREIGN_KEY_CHECKS=0;" . $sql . "SET FOREIGN_KEY_CHECKS=1;");

        $query = NULL;
    }
}