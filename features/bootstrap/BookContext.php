<?php

use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\RawMinkContext;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Tests\Functional\WebTestCase;

class BookContext extends RawMinkContext
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /** @var string */
    protected $authorFilter;

    /** @var string */
    protected $categoryFilter;

    /** @var string */
    protected $apiPath;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Given /^I am an api consumer$/
     */
    public function iAmAnApiConsumer()
    {
        // Set an API key
        $this->getSession()->getDriver()->getClient()->setServerParameter('HTTP_API-TOKEN', 'SOME-API-KEY');
    }

    /**
     * @When /^I filter by author "([^"]*)"$/
     * @param $authorName
     */
    public function iFilterByAuthor($authorName)
    {
        $this->authorFilter = $authorName;
    }

    /**
     * @Given /^I filter by category "([^"]*)"$/
     * @param $category
     */
    public function iFilterByCategory($category)
    {
        $this->categoryFilter = $category;
    }

    /**
     * @Then /^I should receive a (\d+) response$/
     * @param $statusCode
     */
    public function iShouldReceiveAResponse($statusCode)
    {
        $url = $this->apiPath;

        $query = http_build_query($this->getFilters());
        if ($query) {
            $url .= '?' . $query;
        }

        $this->visitPath($url);
        WebTestCase::assertEquals($statusCode, $this->getSession()->getStatusCode());
    }

    /**
     * @When /^I query for a list of books$/
     */
    public function iQueryForAListOfBooks()
    {
        $this->apiPath = '/books';
    }

    /**
     * @When /^I query the api for a list of categories$/
     */
    public function iQueryTheApiForAListOfCategories()
    {
        $this->apiPath = '/categories';
    }

    /**
     * Sanitise the possible filters
     * @return array
     */
    public function getFilters()
    {
        $filters = [
            'author' => $this->authorFilter,
            'category' => $this->categoryFilter,
        ];

        $filters = array_filter($filters);

        return $filters;
    }

    /**
     * @Given /^the body should contain (\d+) results$/
     * @Given /^the body should contain (\d+) result$/
     * @param integer $numberOfResults
     */
    public function theBodyShouldContainResults($numberOfResults)
    {
        $content = json_decode($this->getSession()->getPage()->getContent(), true);
        WebTestCase::assertEquals($numberOfResults, count($content['data']));
    }

    /**
     * @Given /^the body should contain "(.*?)"$/
     */
    public function theBodyShouldContain($text)
    {
        WebTestCase::assertContains($text, $this->getSession()->getPage()->getContent());
    }

    /**
     * @Given /^the content type should be "([^"]*)"$/
     */
    public function theContentTypeShouldBe($responseType)
    {
        WebTestCase::assertEquals($responseType, $this->getSession()->getResponseHeader('content-type'));
    }

    /**
     * @When /^I create the following book$/
     * @param TableNode $table
     */
    public function iCreateTheFollowingBook(TableNode $table)
    {
        $postData = array_combine($table->getRow(0), $table->getRow(1));
        $this->apiPath = '/books';

        $this->getSession()->getDriver()->getClient()->request ('POST', $this->apiPath, $postData);
    }
}