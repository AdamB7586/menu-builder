<?php
namespace Menu\Tests;

use PHPUnit\Framework\TestCase;
use Menu\Navigation;
use InvalidArgumentException;

class NavigationTest extends TestCase {
    protected $navigation;
    
    protected function setUp(){
        error_reporting(E_ALL && ~E_NOTICE);
        $this->navigation = new Navigation();
    }
    
    protected function tearDown(){
        $this->navigation = null;
    }
    
    /**
     * @covers Menu\Navigation::setNavigationClass
     * @covers Menu\Navigation::getNavigationClass
     */
    public function testSetNavigationClass(){
        $this->assertEquals('nav navbar-nav', $this->navigation->getNavigationClass());
        $this->assertObjectHasAttribute('startLevel', $this->navigation->setNavigationClass('my-nav-class'));
        $this->assertEquals('my-nav-class', $this->navigation->getNavigationClass());
        $this->assertObjectHasAttribute('startLevel', $this->navigation->setNavigationClass(false));
        $this->assertEmpty($this->navigation->getNavigationClass());
        $this->assertObjectHasAttribute('startLevel', $this->navigation->setNavigationClass(42));
        $this->assertEmpty($this->navigation->getNavigationClass());
    }
    
    /**
     * @covers Menu\Navigation::setNavigationID
     * @covers Menu\Navigation::getNavigationID
     */
    public function testSetNavigationID(){
        $this->assertEmpty($this->navigation->getNavigationID());
        $this->assertObjectHasAttribute('startLevel', $this->navigation->setNavigationID('nav-id'));
        $this->assertEquals('nav-id', $this->navigation->getNavigationID());
        $this->assertObjectHasAttribute('startLevel', $this->navigation->setNavigationID(false));
        $this->assertEmpty($this->navigation->getNavigationID());
        $this->assertObjectHasAttribute('startLevel', $this->navigation->setNavigationID(42));
        $this->assertEmpty($this->navigation->getNavigationID());
    }
    
    /**
     * @covers Menu\Navigation::setActiveClass
     * @covers Menu\Navigation::getActiveClass
     */
    public function testSetActiveClass(){
        $this->assertEquals('active', $this->navigation->getActiveClass());
        $this->assertObjectHasAttribute('startLevel', $this->navigation->setActiveClass('my-active-class'));
        $this->assertEquals('my-active-class', $this->navigation->getActiveClass());
        $this->assertObjectHasAttribute('startLevel', $this->navigation->setActiveClass(false));
        $this->assertEquals('my-active-class', $this->navigation->getActiveClass());
        $this->assertObjectHasAttribute('startLevel', $this->navigation->setActiveClass(42));
        $this->assertEquals('my-active-class', $this->navigation->getActiveClass());
    }
    
    /**
     * @covers Menu\Navigation::setCurrentURI
     * @covers Menu\Navigation::getCurrentURI
     * @covers Menu\Helpers\Levels::getCurrent
     * @covers Menu\Helpers\Levels::getCurrentItem
     * @covers Menu\Helpers\Levels::iterateItems
     */
    public function testSetCurrentURI(){
        
        $this->assertObjectHasAttribute('startLevel', $this->navigation->setCurrentURI('/my-current-uri'));
        $this->assertEquals('/my-current-uri', $this->navigation->getCurrentURI());
        $this->expectException(InvalidArgumentException::class);
        $this->navigation->setCurrentURI(false);
        $this->assertEquals('/my-current-uri', $this->navigation->getCurrentURI());
        $this->navigation->setCurrentURI(42);
        $this->assertEquals('/my-current-uri', $this->navigation->getCurrentURI());
    }
    
    /**
     * @covers Menu\Navigation::setStartLevel
     * @covers Menu\Navigation::getStartLevel
     */
    public function testSetStartLevel(){
        $this->assertEquals(0, $this->navigation->getStartLevel());
        $this->assertObjectHasAttribute('startLevel', $this->navigation->setStartLevel(1));
        $this->assertEquals(1, $this->navigation->getStartLevel());
        $this->assertObjectHasAttribute('startLevel', $this->navigation->setStartLevel(false));
        $this->assertEquals(1, $this->navigation->getStartLevel());
        $this->assertObjectHasAttribute('startLevel', $this->navigation->setStartLevel('help'));
        $this->assertEquals(1, $this->navigation->getStartLevel());
    }
    
    /**
     * @covers Menu\Navigation::setMaxLevels
     * @covers Menu\Navigation::getMaxLevels
     */
    public function testSetMaxLevels(){
        $this->assertEquals(5, $this->navigation->getMaxLevels());
        $this->assertObjectHasAttribute('startLevel', $this->navigation->setMaxLevels(1));
        $this->assertEquals(1, $this->navigation->getMaxLevels());
        $this->assertObjectHasAttribute('startLevel', $this->navigation->setMaxLevels(false));
        $this->assertEquals(1, $this->navigation->getMaxLevels());
        $this->assertObjectHasAttribute('startLevel', $this->navigation->setMaxLevels('help'));
        $this->assertEquals(1, $this->navigation->getMaxLevels());
    }
    
    /**
     * @covers Menu\Navigation::addLink
     * @covers Menu\Navigation::addLinks
     * @covers Menu\Navigation::setCurrentURI
     * @covers Menu\Navigation::hasLink
     * @covers Menu\Navigation::toArray
     * @covers Menu\Helpers\Sorting::sort
     * @covers Menu\Helpers\Sorting::sortByOrder
     * @covers Menu\Helpers\Sorting::sortChildElements
     */
    public function testAddLink(){
        $this->assertFalse($this->navigation->hasLink('/my-link'));
        $this->assertObjectHasAttribute('startLevel', $this->navigation->addLink('My Link', '/my-link'));
        $this->assertTrue($this->navigation->hasLink('/my-link'));
        $this->assertObjectHasAttribute('startLevel', $this->navigation->addLink('New Link', '/my-new-link', array('order' => -100)));
        $this->assertTrue($this->navigation->hasLink('/my-link'));
        $this->assertEquals('/my-new-link', $this->navigation->toArray()[0]['uri']);
        $this->expectException(InvalidArgumentException::class);
        $this->assertObjectHasAttribute('startLevel', $this->navigation->addLink(5213, '/invalid-addition'));
        $this->assertFalse($this->navigation->hasLink('/invalid-addition'));
        $this->assertObjectHasAttribute('startLevel', $this->navigation->addLink('Invalid', '/invalid-addition', 'should_be_array'));
        $this->assertFalse($this->navigation->hasLink('/invalid-addition'));
    }
    
    /**
     * @covers Menu\Navigation::addLinks
     * @covers Menu\Navigation::setCurrentURI
     * @covers Menu\Navigation::toArray
     * @covers Menu\Helpers\Sorting::sort
     * @covers Menu\Helpers\Sorting::sortByOrder
     * @covers Menu\Helpers\Sorting::sortChildElements
     */
    public function testAddLinks(){
        include dirname(__FILE__).DIRECTORY_SEPARATOR.'sample_data'.DIRECTORY_SEPARATOR.'array.php';
        $this->assertObjectHasAttribute('startLevel', $this->navigation->addLinks($nav_array));
        $this->assertGreaterThan(3, count($this->navigation->toArray()));
    }
    
    /**
     * @covers Menu\Navigation::hasLinks
     * @covers Menu\Navigation::hasLink
     */
    public function testHasLinks(){
        include dirname(__FILE__).DIRECTORY_SEPARATOR.'sample_data'.DIRECTORY_SEPARATOR.'array.php';
        $this->navigation->addLinks($nav_array);
        $this->assertFalse($this->navigation->hasLinks(['/link-doesnt-exists', '/', '/child/hippo']));
        $this->assertTrue($this->navigation->hasLinks(['/child/child/help', '/', '/child/hippo']));
        $this->assertFalse($this->navigation->hasLinks(42));
        $this->assertFalse($this->navigation->hasLinks(true));
        $this->assertFalse($this->navigation->hasLinks('/'));
    }
    
    /**
     * @covers Menu\Navigation::toArray
     */
    public function testToArray(){
        $this->navigation->addLink('Home' , '/');
        $this->assertEquals(1, count($this->navigation->toArray()));
        $this->assertArrayHasKey('uri', $this->navigation->toArray()[0]);
        $this->navigation->addLink('New Link' , '/new-link');
        $this->assertEquals(2, count($this->navigation->toArray()));
        $this->assertEquals('New Link', $this->navigation->toArray()[1]['label']);
        $this->assertArrayHasKey('uri', $this->navigation->toArray()[1]);
    }
    
    /**
     * @covers Menu\Navigation::__construct
     * @covers Menu\Navigation::render
     * @covers Menu\Navigation::getActiveClass
     * @covers Menu\Navigation::getStartLevel
     * @covers Menu\Navigation::getMaxLevels
     * @covers Menu\Builder\Menu::build
     * @covers Menu\Builder\Menu::createMenuLevel
     * @covers Menu\Builder\Link::formatLink
     * @covers Menu\Builder\Link::title
     * @covers Menu\Builder\Link::target
     * @covers Menu\Builder\Link::rel
     * @covers Menu\Builder\Link::label
     * @covers Menu\Builder\Link::href
     * @covers Menu\Builder\Link::htmlClass
     * @covers Menu\Builder\Link::htmlID
     * @covers Menu\Helpers\URI::getHref
     * @covers Menu\Helpers\URI::setURI
     * @covers Menu\Helpers\URI::getURI
     * @covers Menu\Helpers\URI::setAnchorPoint
     * @covers Menu\Helpers\URI::getAnchorPoint
     */
    public function testRender(){
        $this->navigation->addLink('Home' , '/');
        $this->assertEquals('<ul class="nav navbar-nav"><li><a href="/" title="Home">Home</a></li></ul>', $this->navigation->render());
    }
    
    /**
     * @covers Menu\Navigation::__construct
     * @covers Menu\Navigation::renderBreadcrumb
     * @covers Menu\Builder\Breadcrumb::setBreadcrumbSeparator
     * @covers Menu\Builder\Breadcrumb::getBreadcrumbSeparator
     * @covers Menu\Builder\Breadcrumb::setBreadcrumbElement
     * @covers Menu\Builder\Breadcrumb::getBreadcrumbElement
     * @covers Menu\Builder\Breadcrumb::setBreacrumbLinks
     * @covers Menu\Builder\Breadcrumb::getBreacrumbLinks
     * @covers Menu\Builder\Breadcrumb::setActiveClass
     * @covers Menu\Builder\Breadcrumb::getActiveClass
     * @covers Menu\Builder\Breadcrumb::isBreadcrumbList
     * @covers Menu\Builder\Breadcrumb::createBreadcrumb
     * @covers Menu\Builder\Link::htmlClass
     * @covers Menu\Builder\Link::label
     * @covers Menu\Builder\Link::formatLink
     */
    public function testRenderBreadcrumb(){
        $this->navigation->addLink('Home' , '/');
        $this->assertEquals('<ul class="breadcrumb"><li class="breadcrumb-item"><a href="/" title="Home">Home</a></li></ul>', $this->navigation->renderBreadcrumb());
    }
}
