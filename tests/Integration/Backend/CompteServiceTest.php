<?php

namespace App\Tests\Integration\Backend;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Backend\CompteService;
use App\Backend\ClientService;
use App\Entity\Compte;
use function PHPUnit\Framework\assertEquals;

class CompteServiceTest extends KernelTestCase
{
    private static $cnx;
    private $compteService;
    private $clientService;
    private $client;
    
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
        
        // on récupère le client dont l'Id est 1
        $this->client = $this->clientService->rechercherClientParId(1);
        
        
    }
    
    public function tearDown(): void
    {
        // Nettoyage du jeu de données
        self::$cnx->exec(file_get_contents('tests/scripts/clean.sql'));
    }
    
    public function testRechercherCompteParNumero(): void
    {
        // on crée l'objet Compte
        $compte = new Compte();
        $compte->setNumero(78954263);
        $compte->setSolde("5000.00");
        $compte->setClient($this->client);
        
        // on appelle la méthode à tester
        $compteRecupere = $this->compteService->rechercherCompteParNumero(78954263);
        
        // on compare l'objet récupéré avec l'objet de référence
        $this->assertEquals($compte, $compteRecupere);
    }
    
    public function testRechercherComptesClient(): void
    {
        // on appelle la méthode à tester
        $tabComptesRecuperes = $this->compteService->rechercherComptesClient($this->client);
        
        // pour le client n°1, on a un seul compte en base (mais on pourrait en avoir plusieurs)
        $this->assertCount(1, $tabComptesRecuperes);
        
        // on teste que tous les comptes récupérés ont bien le client 1
        foreach ($tabComptesRecuperes as $compteRecupere)
        {
            $this->assertEquals($this->client, $compteRecupere->getClient());
        }
    }

    public function testAjouterCompte(): void
    {
        // on crée chaque objet Compte de référence
        $compte = new Compte();
        $compte->setNumero(99999999);
        $compte->setSolde("99999.00");
        $compte->setClient($this->client);
        
        // on ajoute le compte
        $this->compteService->ajouterCompte($compte);
        
        $listeComptes = $this->compteService->rechercherComptesClient($this->client);
        
        // on doit maintenant avoir 2 comptes pour le client
        $this->assertCount(2, $listeComptes);
        
        // on recherche le compte et on le compare à celui qu'on a initialisé
        $compteRecupere = $this->compteService->rechercherCompteParNumero(99999999);
        
        // on compare l'objet récupéré avec l'objet de référence
        $this->assertEquals($compte, $compteRecupere);
       
    }
    
}
