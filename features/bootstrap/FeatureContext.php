<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

use StoneApple\Application as StoneAppleApplication;
use StoneApple\Helper\Helper;

use StoneAppleDev\PublicSchema\Post;

require_once 'PHPUnit/Autoload.php';
require_once 'PHPUnit/Framework/Assert/Functions.php';

/**
 * Features context.
 */
class FeatureContext extends BehatContext
{
    /** @var \StoneApple\Application */
    var $application;

    /** @var string */
    var $env;

    /**
     * Initializes context.
     * Every scenario gets it's own context object.
     *
     * @param array $parameters context parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters)
    {
        $this->env = isset($parameters['env'])?$parameters['env']:"test";
    }

    /**
     * @BeforeScenario
     */
    public function setup()
    {
        $this->application = new StoneAppleApplication($this->env);

        $connection = $this->getPommConnection();
        $map = $connection->getMapFor('\StoneAppleDev\PublicSchema\Post')
            ->truncate();
    }

    private function getPommConnection()
    {
        return $this->application['pomm']->getDatabase()->getConnection();
    }

    /**
     * @When /^I access the url "([^"]*)"$/
     */
    public function iAccessTheUrl($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Then /^I should see "([^"]*)" articles$/
     */
    public function iShouldSeeArticles($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Given /^there is an article with title "([^"]*)"$/
     */
    public function thereIsAnArticleWithTitle($title)
    {
        $connection = $this->getPommConnection();
        $map = $connection->getMapFor('\StoneAppleDev\PublicSchema\Post');

        $article = new Post();
        $article->set('title', $title);
        $article->set('slug', Helper::slugify($title));
        $article->set('body', sprintf(
            "The bird flew over the Cookoo's nest crying `%s'",
            $title
        ));

        $map->saveOne($article);
    }

    /**
     * @Then /^I cannot add a new article with title "([^"]*)"$/
     */
    public function iCannotAddANewArticleWithTitle($title)
    {
        try {
            $this->thereIsAnArticleWithTitle($title);
        } catch(Pomm\Exception\SqlException $ex) {
            // unique violation
            assertEquals(23505, $ex->getSQLErrorState());
        }
    }
}
