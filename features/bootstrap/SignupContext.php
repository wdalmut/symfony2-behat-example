<?php

use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkAwareContext;
use Behat\Mink\Mink;

use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Symfony2Extension\Driver\KernelDriver;
use \PHPUnit_Framework_Assert as Assert;

/**
 * Behat context class.
 */
class SignupContext implements SnippetAcceptingContext, MinkAwareContext
{
    private $mink;
    private $minkParameters;
    private $entityManager;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context object.
     * You can also pass arbitrary arguments to the context constructor through behat.yml.
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function setMink(Mink $mink)
    {
        $this->mink = $mink;
    }

    public function setMinkParameters(array $parameters)
    {
        $this->minkParameters = $parameters;
    }

    /**
     * @BeforeScenario
     */
    public function clearDatabase()
    {
        $purger = new ORMPurger($this->entityManager);
        $purger->purge();
    }

    /**
     * @Given I am an interested user with email :arg1
     */
    public function anInterestedUserWithEmail($email)
    {
        // shared in the feature context
        $this->email = $email;

        // Check that is an interested user and not an existing one
        Assert::assertNull(
            $this->entityManager->getRepository("CorleyBaseBundle:User")->findOneByEmail($email)
        );
    }

    /**
     * @When I fill all personal fields
     */
    public function heFillsAllPersonalFields()
    {
        $this->mink->getSession()->getPage()->find("css", "#corley_bundle_basebundle_user_email")->setValue($this->email);
        $this->mink->getSession()->getPage()->find("css", "#corley_bundle_basebundle_user_name")->setValue(rand(0,100000));
    }

    /**
     * @When I confirm my registration
     */
    public function heConfirmsTheRegistration()
    {
        $client = $this->mink->getSession()->getDriver()->getClient();
        $client->followRedirects(false);

        $this->mink->getSession()->getPage()->find("css", "#corley_bundle_basebundle_user_submit")->click();
    }

    /**
     * @Then I should be registered as an unconfirmed user
     */
    public function heShouldBeRegisteredAsAnUnconfirmedUser()
    {
        $this->entityManager->clear();
        $entity = $this->entityManager->getRepository("CorleyBaseBundle:User")->findOneByEmail($this->email);

        Assert::assertNotNull($entity);
        Assert::assertEquals($this->email, $entity->getEmail());
        Assert::assertFalse($entity->isConfirmed());
    }

    /**
     * @Then I should receive the registration email
     */
    public function heShouldReceiveTheRegistrationEmail()
    {
        $driver = $this->mink->getSession()->getDriver();
        if (!$driver instanceof KernelDriver) {
            throw new \RuntimeException("Only kernel drivers");
        }

        $profile = $driver->getClient()->getProfile();
        if (false === $profile) {
            throw new \RuntimeException("Profiler is disabled");
        }

        $collector = $profile->getCollector('swiftmailer');
        Assert::assertCount(1, $collector->getMessages());
    }

    /**
     * @Then I should be in the reserved area
     */
    public function heShouldBeInTheReservedArea()
    {
        $client = $this->mink->getSession()->getDriver()->getClient();
        $client->followRedirects(true);
        $client->followRedirect();

        $this->mink->assertSession()->pageTextContains("Hello reserved area!");
    }

}
