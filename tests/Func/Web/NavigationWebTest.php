<?php

namespace App\Tests\Func\Web;

use PHPUnit\Framework\TestCase;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverDimension;

class NavigationWebTest extends TestCase
{
    private $webDriver;
    private $baseUrl;
    
    public function setUp(): void
    {
//         $this->webDriver = RemoteWebDriver::create('http://localhost:4444', DesiredCapabilities::firefox());
        $this->baseUrl = "http://localhost";
    }
    
    public function tearDown(): void
    {
        $this->webDriver->quit();
    }

    public function specifierNavigateur()
    {
        return [
            ['4444', DesiredCapabilities::firefox()],
            ['4445', DesiredCapabilities::chrome()]
        ];
    }
    
    /**
     * @dataProvider specifierNavigateur
     */
    public function testConnexionClient($port, $caps): void
    {

        $this->webDriver = RemoteWebDriver::create('http://localhost:'.$port, $caps);
        
        // on ouvre la page d'accueil
        $this->webDriver->get($this->baseUrl . '/');
        
        // taille de la fenêtre
        $this->webDriver->manage()->window()->setSize(new WebDriverDimension(1920, 1080));
        
        // on vérifie le titre de la page d'accueil
        $titrePage = $this->webDriver->findElement(WebDriverBy::cssSelector('h2'))->getText();
        $this->assertEquals('Bienvenue sur votre Banque en ligne !!!', $titrePage);
        
        // on fait un clic sur le lien "Accès client" sur id=link-client
        $this->webDriver->findElement(WebDriverBy::id('link-client'))->click();
        
        // on vérifie le titre2 'Identification Client'
        $titre2Page = $this->webDriver->findElement(WebDriverBy::cssSelector('h3'))->getText();
        $this->assertEquals('Identification Client', $titre2Page);
        
        // on remplit le formulaire l'identifiant et le mot de passe
        $this->webDriver->findElement(WebDriverBy::id('identification_form_identifiant'))->sendKeys('1');
        $this->webDriver->findElement(WebDriverBy::id('identification_form_mot_de_passe'))->sendKeys('secret');
        $this->webDriver->findElement(WebDriverBy::id('identification_form_submit'))->click();
        
        // on vérifie qu'on a sur la page "Bonjour Robert DUPONT !"
        $lienAccueilClient = $this->webDriver->findElement(WebDriverBy::linkText('Bonjour Robert DUPONT !'));
        $this->assertNotNull($lienAccueilClient);
        
        // on clique sur "Mes opérations"
        $this->webDriver->findElement(WebDriverBy::id('navbarDropdown'))->click();
        
        // on clique sur "Mes comptes"
        $this->webDriver->findElement(WebDriverBy::linkText('Mes Comptes'))->click();
        
        // on vérifie le titre
        $titre3Page = $this->webDriver->findElement(WebDriverBy::cssSelector('h3'))->getText();
        $this->assertEquals('Résumé de votre situation', $titre3Page);
        
        // on vérifie le n° de compte
        $numCompte = $this->webDriver->findElement(WebDriverBy::cssSelector('td:nth-child(1)'))->getText();
        $this->assertEquals('78954263', $numCompte);
        
        // on vérifie le solde
        $soldeCompte = $this->webDriver->findElement(WebDriverBy::cssSelector('td:nth-child(2)'))->getText();
        $this->assertEquals('5000.00 €', $soldeCompte);
        
    }
}
