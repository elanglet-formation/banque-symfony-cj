<?php

namespace App\Tests\Integration\Backend;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Backend\ClientService;
use App\Entity\Client;

class ClientServiceTest extends KernelTestCase
{
    private static $cnx;
    private $clientService;
    
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
        
        // récupérer le ClientService
        $this->clientService = new ClientService($entityManager);
    }

    public function tearDown(): void
    {
        // Nettoyage du jeu de données
        self::$cnx->exec(file_get_contents('tests/scripts/clean.sql'));
    }
    
    public function testRechercherClientParId(): void
    {
        // on crée l'objet Client de référence
        $client = new Client();
        $client->setId(1);
        $client->setNom("DUPONT");
        $client->setPrenom("Robert");
        $client->setAdresse("40, rue de la Paix");
        $client->setVille("Paris");
        $client->setCodepostal("75007");
        $client->setMotdepasse("secret");
        
        // on appelle la méthode à tester
        $clientRecupere = $this->clientService->rechercherClientParId(1);
        
        // on compare l'objet récupéré avec l'objet de référence
        $this->assertEquals($client, $clientRecupere);
    }
    
    public function testRechercherTousLesClients(): void
    {
        $nbClients = 2;
        // on appelle la méthode à tester
        $listeClients = $this->clientService->rechercherTousLesClients();
        
        // on compare le nb d'objets récupérés
        $this->assertCount($nbClients, $listeClients);
    }
    
    public function testAjouterClient(): void
    {
        // on crée chaque objet Client de référence
        $client = new Client();
        $client->setId(3);
        $client->setNom("SOUDY");
        $client->setPrenom("Philippe");
        $client->setAdresse("2, rue des Féages");
        $client->setVille("Bruz");
        $client->setCodepostal("35135");
        $client->setMotdepasse("secretPhilippe");
        
        // on ajoute le client
        $this->clientService->ajouterClient($client);

        $listeClients = $this->clientService->rechercherTousLesClients();
        
        // on doit maintenant avoir 3 clients
        $this->assertCount(3, $listeClients);
        
        // on recherche le client et on le compare à celui qu'on a initialisé
        $clientRecupere = $this->clientService->rechercherClientParId(3);
        
        // on compare l'objet récupéré avec l'objet de référence
        $this->assertEquals($client, $clientRecupere);
        
    }
    
}
