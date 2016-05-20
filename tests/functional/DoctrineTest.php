<?php

namespace Tests\Functional;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Tests\Utils\Person;

final class DoctrineTest extends FunctionalTestCase
{
    /** @test */
    public function simple_object()
    {
        $person = $this->fixture("company.people.ricard");

        $this->assertEquals(new Person("Ricard", 30), $person);

        $retrievedPerson = $this->findPerson(1);

        $this->assertEquals($person, $retrievedPerson);
        $this->assertNotSame($person, $retrievedPerson);
    }

    //---[ Helpers ]--------------------------------------------------------------------//

    /**
     * @param int $personId
     * @return Person|null
     */
    private function findPerson($personId)
    {
        $pdo = new \PDO(
                "mysql:host=" . getenv("TEST_DB_HOST") . ";dbname=" . getenv("TEST_DB_NAME"),
                getenv("TEST_DB_USER"),
                getenv("TEST_DB_PASSWORD"));

        $configuration = Setup::createXMLMetadataConfiguration([__DIR__ . "/../Utils/resources/doctrine/"]);

        $em = EntityManager::create(['pdo' => $pdo], $configuration);
        $person = $em->find(Person::class, $personId);

        return $person;
    }
}
