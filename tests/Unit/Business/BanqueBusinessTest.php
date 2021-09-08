<?php

namespace App\Tests\Unit\Business;

use PHPUnit\Framework\TestCase;
use App\Entity\Client;
use App\Backend\ClientService;
use App\Backend\CompteService;
use App\Business\BanqueBusiness;

class BanqueBusinessTest extends TestCase
{
    private $banqueBusiness;
    private $client;
    
    public function setUp(): void
    {
        // on crée un Stub pour ClientService.
        // ==> une implémentation qui ne contient que la méhode 'rechercherClientParId'
        // renvoyant toujours le même objet Client
        $this->client = new Client();
        $this->client->setId(1);
        $this->client->setMotdepasse("motDePasse");
        
        $clientService = $this->createMock(ClientService::class);
        // on spécifie la méthode  à définir dans cette implémentation vide
        $clientService->method('rechercherClientParId')
                        ->willReturn($this->client);
        
        // on crée un stub pour CompteService
        $compteService = $this->createMock(CompteService::class);
        
        // on instancie l'objet à tester
        $this->banqueBusiness = new BanqueBusiness($clientService, $compteService);
        
    }
    
    public function testAuthentifier(): void
    {
        // on appelle la méthode à tester avec des paramètres cohérents par rapport à ce que renvoit le stub
        $clientReturned = $this->banqueBusiness->authentifier(1, "motDePasse");
        $this->assertNotNull($clientReturned);
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
    

}
