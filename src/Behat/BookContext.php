<?php

namespace App\Behat;

use Behat\MinkExtension\Context\RawMinkContext;
use Symfony\Bundle\FrameworkBundle\Tests\Functional\WebTestCase;

class BookContext extends RawMinkContext
{
    /** @var string */
    protected $authorFilter;

    /** @var string */
    protected $categoryFilter;

    /** @var string */
    protected $apiPath;

    /**
     * @Given /^I am an api consumer$/
     */
    public function iAmAnApiConsumer()
    {
        // We should set some sort of API Key here

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
     * @throws \Behat\Mink\Exception\ExpectationException
     */
    public function iShouldReceiveAResponse($statusCode)
    {
        $url = $this->apiPath;

        $query = http_build_query($this->getFilters());
        if ($query) {
            $url .= '?' . $query;
        }

        $this->visitPath($url);
        $this->assertSession()->statusCodeEquals($statusCode);
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
}