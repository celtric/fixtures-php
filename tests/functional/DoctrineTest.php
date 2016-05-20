<?php

namespace Tests\Functional;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Tests\Utils\Person;

final class DoctrineTest extends FunctionalTestCase
{
    /** @var EntityManager */
    private static $em;

    /**
     * @inheritDoc
     */
    public static function setUpBeforeClass()
    {
        $pdo = new \PDO(
            "mysql:host=" . getenv("TEST_DB_HOST") . ";dbname=" . getenv("TEST_DB_NAME"),
            getenv("TEST_DB_USER"),
            getenv("TEST_DB_PASSWORD"));

        $pdo->query("TRUNCATE TABLE people;");

        $configuration = Setup::createXMLMetadataConfiguration([__DIR__ . "/../Utils/resources/doctrine/"]);

        static::$em = EntityManager::create(["pdo" => $pdo], $configuration);
    }

    /** @test */
    public function simple_object()
    {
        $person = $this->fixture("company.people.ricard");

        $this->assertEqualsIgnoringId(new Person("Ricard", 30), $person);

        $retrievedPerson = $this->findPerson(1);

        $this->assertEquals($person, $retrievedPerson);
        $this->assertNotSame($person, $retrievedPerson);
    }

    //---[ Helpers ]--------------------------------------------------------------------//

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
     * @param int $personId
     * @return Person|null
     */
    private function findPerson($personId)
    {
        return static::$em->find(Person::class, $personId);
    }

    /**
     * @param Person $expected
     * @param Person $actual
     */
    private function assertEqualsIgnoringId(Person $expected, Person $actual)
    {
        $actual = clone $actual;
        $reflectedProperty = new \ReflectionProperty($actual, "id");
        $reflectedProperty->setAccessible(true);
        $reflectedProperty->setValue($actual, null);

        $this->assertEquals($expected, $actual);
    }
}
