<?php

namespace App\Tests\Unit\Backend;

use PHPUnit\Framework\TestCase;
use Doctrine\ORM\EntityManagerInterface;
use App\Backend\ClientService;
use App\Entity\Client;
use Doctrine\Persistence\ObjectRepository;

class ClientServiceTest extends TestCase
{
    
    // on déclare l'ojet à tester
    private $clientService;
    // on déclare les mocks nécessaires
    // 1. un mock sur EntityManagerInterface
    private $entityManager;
    // 2. un mock sur ObjectRepository
    private $repo;
    
    public function setUp() : void
    {
        // on crée les mocks
        // 1. entityManagerInterface
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        // 2. ObjectRepository
        $this->repo = $this->createMock(ObjectRepository::class);
        
        // on instancie l'objet à tester en lui passant le mock
        $this->clientService = new ClientService($this->entityManager);
    }

    public function testRechercherClientParId()
    {
        // on crée l'objet que l'on s'attend à recevoir    
        $client = new Client();
        $client->setId(1);
        
        // on décrit le comportement
        $this->entityManager
            ->expects($this->once())        // un seul appel 
            ->method('getRepository')       // ... de la méthode getRepository ...
            ->with('App:Client')            // ... avec le paramètre App:client ...
            ->willReturn($this->repo);      // ... retourne notre Mock sur ObjectRepository ...
        
        $this->repo
            ->expects($this->once())        // un seul appel
            ->method('find')                // ... à la méthode 'find' ...
            ->with(1)                       // ... avec le paramètre 1 ...
            ->willReturn($client);          // ... retourne bien notre client ...
        
        // on appelle la méthode à tester
        $returnedClient = $this->clientService->rechercherClientParId(1);
        
        // assertion : on vérifie que l'objet retourné est le même que celui qui est attendu
        $this->assertEquals($client, $returnedClient);
    }
    
    public function testAjouterClient(): void
    {
        // on crée l'objet nécessaire pour l'appel de la métode à tester
        $client = new Client();
        // on décrit le comportement attendu
        // 1. on s'attend à avoir 1 et 1 seul appele à 'persist' avec l'objet $client en paramètre
        $this->entityManager
            ->expects($this->once())            // 1 et 1 seul appel
            ->method('persist')                 // ... à persist ...
            ->with($client);                    // ... avec l'objet $client en paramètre ...
        
        // 2. on s'attend à avoir 1 et 1 seul appel à 'flush'
        $this->entityManager
            ->expects($this->once())            // 1 et 1 seul appel
            ->method('flush');                  // ... à flush ...
        
        // on exécute la méthode à tester, son exécution doit dérouler le scénario décrit
        $this->clientService->ajouterClient($client);
    }
    
 
    
}
