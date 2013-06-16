<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

use Behat\MinkExtension\Context\MinkContext;

use StoneApple\Application as StoneAppleApplication;
use StoneApple\Helper\Helper;

use StoneAppleDev\PublicSchema\Post;

require_once 'PHPUnit/Autoload.php';
require_once 'PHPUnit/Framework/Assert/Functions.php';

/**
 * Features context.
 */
class FeatureContext extends MinkContext
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
        $this->application = new StoneAppleApplication($this->env);
    }

    /**
     * @BeforeScenario
     */
    public function emptyDatabase()
    {
        $connection = $this->getPommConnection();
        $map = $connection->getMapFor('\StoneAppleDev\PublicSchema\Post')
            ->truncate();
    }

    private function getPommConnection()
    {
        return $this->application['pomm']->getDatabase()->getConnection();
    }

    private function pageIsFound()
    {
        $this->assertSession()->statusCodeEquals(200);
    }

    /**
     * @Then /^I should see "([^"]*)" articles$/
     */
    public function iShouldSeeArticles($count)
    {
        $this->pageIsFound();
        $this->assertSession()->elementsCount('css', 'article', $count);
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
            assertEquals(23505, $ex->getSQLErrorState(), "throws a 'unique' violation");
        }
    }

    /**
     * @Given /^I should see that one of the articles has the title "([^"]*)"$/
     */
    public function iShouldSeeThatOneOfTheArticlesHasTheTitle($title)
    {
        $nodes = $this->getSession()->getPage()->findAll('css', 'article h4');

        $found = false;
        foreach($nodes as $element) {
            if($element->getText() == $title) {
                $found = true;
                break;
            }
        }
        
        assertTrue($found, "the title exists");
    }

    /**
     * @Then /^I should see the article with title "([^"]*)"$/
     */
    public function iShouldSeeTheArticleWithTitle($title)
    {
        $this->pageIsFound();
        $this->assertSession()->elementsCount('css', 'article', 1);
        $this->assertSession()->elementTextContains('css', 'article h4', $title);
    }
}
