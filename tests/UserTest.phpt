<?php //HomepagePresenterTest.phpt

require __DIR__ . '/bootstrap.php';

/**
 * @testCase
 */
class HomepagePresenterTest extends \Tester\TestCase
{

    use \Testbench\TPresenter;

    public function testRenderDefault()
    {
        $this->checkAction('Homepage:default');
    }

    public function testRenderDefaultModule()
    {
        $this->checkAction('Module:Homepage:default');
    }

}

(new HomepagePresenterTest())->run();
