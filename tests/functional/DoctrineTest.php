<?php

namespace Tests\Functional;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Tests\Utils\Person;

final class DoctrineTest extends CeltricStyleFunctionalTestCase
{
    /** @test */
    public function simple_object()
    {
        $this->assertPersistedEquals(new Person("Ricard", 30), $this->fixture("company.people.ricard"));
    }

    /** @test */
    public function complex_object()
    {
        $this->markTestSkipped("Doctrine dies for some reason. TODO: find out why");

        $expected = new Person("Ricard", 30);
        $expected->setFriend(new Person("Phteven", 8));

        $this->assertPersistedEquals($expected, $this->fixture("company.people.ricard_with_friend"));
    }

    //---[ Helpers ]--------------------------------------------------------------------//

    /** @var \PDO */
    private static $pdo;

    /** @var EntityManager */
    private static $em;

    /**
     * @inheritDoc
     */
    public static function setUpBeforeClass()
    {
        foreach (["TEST_DB_HOST", "TEST_DB_NAME", "TEST_DB_USER"] as $envName) {
            if (empty(getenv($envName))) {
                throw new \RuntimeException(
                        "Environment variable {$envName} must be defined in phpunit.xml to be able to execute Doctrine tests.");
            }
        }

        self::$pdo = new \PDO(
                "mysql:host=" . getenv("TEST_DB_HOST") . ";dbname=" . getenv("TEST_DB_NAME"),
                getenv("TEST_DB_USER"),
                getenv("TEST_DB_PASSWORD"));

        self::$pdo->query("DROP TABLE IF EXISTS people;");
        self::$pdo->query("CREATE TABLE people (
            id int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
            name varchar(255) NULL,
            age int unsigned NULL,
            friend int unsigned NULL
        ) ENGINE='InnoDB' COLLATE 'utf8_general_ci';");

        $configuration = Setup::createXMLMetadataConfiguration([__DIR__ . "/../Utils/resources/doctrine/"]);

        static::$em = EntityManager::create(["pdo" => self::$pdo], $configuration);
    }

    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        static::$pdo->query("TRUNCATE TABLE people;");
    }

    /**
     * @inheritDoc
     */
    protected function fixture($fixtureIdentifier)
    {
        $fixture = parent::fixture($fixtureIdentifier);

        static::$em->persist($fixture);
        static::$em->flush();
        static::$em->clear();

        return $fixture;
    }

    /**
     * @param Person $expected
     * @param Person $actual
     */
    private function assertPersistedEquals(Person $expected, Person $actual)
    {
        $this->assertEquals($expected, $this->withoutIds($actual));

        $retrievedPerson = $this->findPerson($actual->id());

        $this->assertEquals($actual, $retrievedPerson);
        $this->assertNotSame($actual, $retrievedPerson);
    }

    /**
     * @param int $personId
     * @return Person|null
     */
    private function findPerson($personId)
    {
        return static::$em->find(Person::class, $personId);
    }

    /**
     * @param mixed $anObject
     * @return mixed
     */
    private function withoutIds($anObject)
    {
        $anObject = clone $anObject;
        $properties = (new \ReflectionClass($anObject))->getProperties();

        foreach ($properties as $property) {
            $property->setAccessible(true);

            if ($property->getName() === "id") {
                $property->setValue($anObject, null);
            }

            if (is_object($property->getValue($anObject))) {
                $property->setValue($anObject, $this->withoutIds($property->getValue($anObject)));
            }
        }

        return $anObject;
    }
}
