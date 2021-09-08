<?php

namespace App\Tests\Integration\Business;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Backend\CompteService;
use App\Backend\ClientService;
use App\Business\BanqueBusiness;
use App\Entity\Client;

class BanqueBusinessTest extends KernelTestCase
{
    private static $cnx;
    private $compteService;
    private $clientService;
    private $client;
    private $banqueBusiness;
    
    public static function setUpBeforeClass(): void
    {
        // mise en place d'une connexion PDO pour la mise en place et le nettoyage de la base de test
        self::$cnx = new \PDO('mysql:host=localhost;port=3306;dbname=banquesf_test', 'banquesf', 'banquesf');
        // pour lever des exceptions en cas de problème de connexion
        self::$cnx->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }
    
    public function setUp(): void
    {
        // initialisation du jeu de données
        self::$cnx->exec(file_get_contents('tests/scripts/init.sql'));
        // récupération de l'entityManager
        $kernel = self::bootKernel();
        $entityManager = $kernel->getContainer()->get('doctrine')->getManager();
        // ou :
        // $kernel = self::bootKernel();
        // $entityManager = self::$container->get('doctrine')->getManager();
        
        // récupérer le CompteService
        $this->compteService = new CompteService($entityManager);
        
        // récupérer le ClientService
        $this->clientService = new ClientService($entityManager);
        
        // récupérer le BanqueBusiness
        $this->banqueBusiness = new BanqueBusiness($this->clientService, $this->compteService);
        
        // on récupère le client dont l'Id est 1
        $this->client = $this->clientService->rechercherClientParId(1);
    }
    
    public function tearDown(): void
    {
        // Nettoyage du jeu de données
        self::$cnx->exec(file_get_contents('tests/scripts/clean.sql'));
    }
    
    public function testAuthentifier(): void
    {
        $client = new Client();
        $client->setId(1);
        $client->setNom("DUPONT");
        $client->setPrenom("Robert");
        $client->setAdresse("40, rue de la Paix");
        $client->setVille("Paris");
        $client->setCodepostal("75007");
        $client->setMotdepasse("secret");
        
        $clientReturned = $this->banqueBusiness->authentifier(1, "secret");
        
        $this->assertSame($this->client, $clientReturned);
    }

    
    public function testAuthentifierEchec(): void
    {
        // on déclare une exception de type \Exception va être déclenchée...
        $this->expectException(\Exception::class);
        // ... avec le message "Erreur d'authentification."
        $this->expectExceptionMessage("Erreur d'authentification.");
        
        // on appelle la méthode à tester avec des paramètres erronés par rapport à ce que renvoit le stub
        $clientReturned = $this->banqueBusiness->authentifier(1, "motDePasseErroné");
    }
    
    public function testMesComptes(): void
    {
        $listeComptes = $this->banqueBusiness->mesComptes($this->client->getId());
        $this->assertCount(1, $listeComptes);
        foreach ($listeComptes as $cpt)
        {
            $this->assertEquals($this->client, $cpt->getClient());
        }
        
    }
}

