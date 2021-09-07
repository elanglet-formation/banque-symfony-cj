<?php

namespace App\Tests\Unit\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\Client;

class ClientTest extends TestCase
{
    private $client;
    
    public function setUp():void
    {
        // l'objet à tester sera recréé avant chaque test
        $this->client = new Client();
        $this->client->setId(1);
        $this->client->setNom("DUPONT");
        $this->client->setPrenom("Robert");
        $this->client->setAdresse("40 rue de la paix");
        $this->client->setVille("PARIS");
        $this->client->setCodepostal("75000");
        $this->client->setMotdepasse("secr3t");
    }
    
    public function testGetId(): void
    {
        // on appelle la méthode à tester
        $id = $this->client->getId();
        // on fait les vérifications (assertions)
        $this->assertEquals(1, $id);
    }

    public function testSetId(): void
    {
        // on change l'id
        $this->client->setId(99);
        // on teste (pas le choix que d'appelet le getId() pour contoler la valeur
        // --> à condition que la méthode getId() ait déjà été testée
        $this->assertEquals(99, $this->client->getId());
    }

    public function testGetNom(): void
    {
        $this->assertEquals("DUPONT", $this->client->getNom());
    }
    
    public function testSetNom(): void
    {
        $this->client->setNom("JOUMEL");
        $this->assertEquals("JOUMEL", $this->client->getNom());
    }

    public function testGetPrenom(): void
    {
        $this->assertEquals("Robert", $this->client->getPrenom());
    }
    
    public function testSetPrenom(): void
    {
        $this->client->setPrenom("Corinne");
        $this->assertEquals("Corinne", $this->client->getPrenom());
    }

    public function testGetAdresse(): void
    {
        $this->assertEquals("40 rue de la paix", $this->client->getAdresse());
    }
    
    public function testSetAdresse(): void
    {
        $this->client->setAdresse("28 rue du Colonel Armand");
        $this->assertEquals("28 rue du Colonel Armand", $this->client->getAdresse());
    }

    public function testGetVille(): void
    {
        $this->assertEquals("PARIS", $this->client->getVille());
    }
    
    public function testSetVille(): void
    {
        $this->client->setVille("SAINT-MALO");
        $this->assertEquals("SAINT-MALO", $this->client->getVille());
    }

    public function testGetCodepostal(): void
    {
        $this->assertEquals("75000", $this->client->getCodepostal());
    }
    
    public function testSetCodepostal(): void
    {
        $this->client->setCodepostal("35400");
        $this->assertEquals("35400", $this->client->getCodepostal());
    }

    public function testGetMotdepasse(): void
    {
        $this->assertEquals("secr3t", $this->client->getMotdepasse());
    }
    
    public function testSetMotdepasse(): void
    {
        $this->client->setMotdepasse("secret");
        $this->assertEquals("secret", $this->client->getMotdepasse());
    }
    
}
