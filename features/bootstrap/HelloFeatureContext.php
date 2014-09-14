<?php

use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Doctrine\ORM\EntityManager;
use Behat\Behat\Tester\Exception\PendingException;

use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Corley\Bundle\BaseBundle\Entity\Book;

/**
 * Behat context class.
 */
class HelloFeatureContext implements SnippetAcceptingContext
{
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

    /**
     * @BeforeScenario
     */
    public function clearDatabase()
    {
        $purger = new ORMPurger($this->entityManager);
        $purger->purge();
    }

    /**
     * @Given there are books
     */
    public function thereAreBooks(TableNode $table)
    {
         foreach ($table->getHash() as $row) {
             $book = new Book();

             $book->setTitle($row["title"]);
             $book->setAuthor($row["author"]);

             $this->entityManager->persist($book);
         }

         $this->entityManager->flush();
    }

}
