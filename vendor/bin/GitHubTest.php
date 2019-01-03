<?php
class GitHubTest extends PHPUnit_Framework_TestCase {

    protected $url = 'http://github.com';
    /**
     * @var \RemoteWebDriver
     */
    protected $webDriver;

	public function setUp()
    {
        $capabilities = array(\WebDriverCapabilityType::BROWSER_NAME => 'chrome');
        $this->webDriver = RemoteWebDriver::create('http://localhost:4444/wd/hub', $capabilities);
    }

    public function tearDown()
    {
        //$this->webDriver->close();
    }

    public function testSearch()
    {
        $this->webDriver->get($this->url);
        
        // find search field by its className
        $search = $this->webDriver->findElement(WebDriverBy::className('js-site-search-focus'));
        $search->click();
        
        // typing into field
        $this->webDriver->getKeyboard()->sendKeys('php-webdriver');
        
        // pressing "Enter"
        $this->webDriver->getKeyboard()->pressKey(WebDriverKeys::ENTER);
        
        $firstResult = $this->webDriver->findElement(
            // some CSS selectors can be very long:
            WebDriverBy::cssSelector('#js-pjax-container > div > div.col-12.col-md-9.float-left.px-2.pt-3.pt-md-0.codesearch-results > div > ul > li:nth-child(1) > div.col-12.col-md-8.pr-md-3 > h3 > a')
        );
        
        $firstResult->click();
        
        // we expect that facebook/php-webdriver was the first result
        $this->assertContains("php-webdriver",$this->webDriver->getTitle());
        
        // checking current url
        $this->assertEquals(
        	'https://github.com/facebook/php-webdriver', 
        	$this->webDriver->getCurrentURL()
        );
    }


    protected function waitForUserInput()
    {
        if(trim(fgets(fopen("php://stdin","r"))) != chr(13)) return;
    }

}
?>