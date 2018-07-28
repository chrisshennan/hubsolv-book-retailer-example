<?php

use App\DataFixtures\SampleFixtures;
use Behat\Behat\Context\Context;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;

class DoctrineContext implements Context
{
    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var SampleFixtures
     */
    private $sampleFixtures;

    public function __construct(EntityManager $entityManager, SampleFixtures $sampleFixtures)
    {
        $this->entityManager = $entityManager;
        $this->sampleFixtures = $sampleFixtures;
    }

    /**
     * Empty the data from the database so we can run new tests with clean data
     */
    public function clearData()
    {
        $purger = new ORMPurger($this->entityManager);
        $purger->purge();
    }

    /**
     * @BeforeScenario @fixtures
     */
    public function loadFixtures()
    {
        $this->clearData();

        // Load the fixture Data
        $this->sampleFixtures->load($this->entityManager);
    }
}