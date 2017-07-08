<?php

namespace _404\Toolz;

use \PHPUnit\Framework\TestCase;
use \_404\Types\Either\Left;
use \_404\Types\Either\Right;

/**
 * Test cases for Toolz
 */

class ToolzTest extends TestCase
{
    protected $tlz;

    protected function setUp()
    {
        $this->tlz = new Toolz();
    }

    /**
     * Test combineEither.
     *
     * Should combine an array of Eithers
     * to an Either containing an array.
     */

    /**
     * Combine Right:s
     */
    public function testCombineEitherRight()
    {
        $arr = [];
        // Test all right
        $arr[] = new Right(1);
        $arr[] = new Right(2);
        $arr[] = new Right(3);

        $rightArray = $this->tlz->combineEither($arr);

        $this->assertTrue($rightArray->isRight());
        $this->assertEquals([1, 2, 3], $rightArray->get());
    }

    /**
     * Combine Left:s
     *
     * The combined Left should contain error from first Left
     * and ignore anything else.
     */
    public function testCombineEitherLeft()
    {
        $arr = [];
        // Test all left
        $arr[] = new Right(1);
        $arr[] = new Left('This is the combined error message');
        $arr[] = new Right(2);
        $arr[] = new Left('This message should be ignored');
        $arr[] = new Right(3);

        $left = $this->tlz->combineEither($arr);

        $this->assertTrue($left->isLeft());
        $this->assertEquals('This is the combined error message', $left->get());
    }

    /**
     * Combine Right:s preserve keys
     *
     * If the combined Either contains array the keys
     * should be preserved.
     */
    public function testCombineEitherRightPreserveKeys()
    {
        $arr = [];
        $arr['first']  = new Right(1);
        $arr['second'] = new Right(2);
        $arr['third']  = new Right(3);

        $rightArray = $this->tlz->combineEither($arr);

        $this->assertTrue($rightArray->isRight());
        $this->assertEquals(
            ['first' => 1, 'second' => 2, 'third' => 3],
            $rightArray->get()
        );
    }

    /**
     * Test partial application tool
     *
     * The partial arguments begin with first argument and so forth.
     */
    public function testPartialApplication()
    {
        $substract = function ($first, $second) {
            return $first - $second;
        };

        // Assert selfcompliance
        $this->assertEquals(42, $substract(72, 30));

        // Test partial
        $substractFrom72 = $this->tlz->partial($substract, 72);

        $this->assertTrue(\is_callable($substractFrom72));
        // If partial arguments start from right the result would be -42.
        $this->assertNotEquals(-42, $substractFrom72(30));
        // If partial arguments start from left then we should have 42.
        $this->assertEquals(42, $substractFrom72(30));
    }

    /**
     * Test eitherEmpty
     */
    public function testEitherEmpty()
    {
        $right = $this->tlz->eitherEmpty(42, 'Ignored');
        $this->assertTrue($right->isRight());
        $this->assertEquals(42, $right->get());

        $left = $this->tlz->eitherEmpty('', 'Empty');
        $this->assertTrue($left->isLeft());
        $this->assertEquals('Empty', $left->get());
    }

    /**
     * Test textBox
     *
     * Test that we get a textbox with text
     */
    public function testTextBox()
    {
        $textbox = $this->tlz->textbox('Some text');

        $this->assertInstanceOf(
            \_404\Components\TextBox\TextBox::class,
            $textbox
        );
        $this->assertEquals('Some text', $textbox->text());
    }

    /**
     * Test filter text
     */
    public function testFilterText()
    {
        $originalString = '
First
http://link.com
# h1
[b]strong[/b]';
        $shouldBeString = "<p>First<br><a href='http://link.com'>http://link.com</a></p><br><br><h1>h1</h1><br><br><p><strong>strong</strong></p><br>";

        $filters = 'markdown, nl2br, link, bbcode';

        $this->assertEquals(
            $shouldBeString,
            $this->tlz->filterText($originalString, $filters)
        );
    }

    /**
     * Test render
     */
    public function testRender()
    {
        $expected = "<h1>Test</h1>
";
        $result = $this->tlz->render(
            ANAX_INSTALL_PATH . '/test/testRender.php',
            ['title' => 'Test']
        );

        $this->assertEquals($expected, $result);
    }

    /**
     * Test render exception
     *
     * Throw exception if no file
     */
    public function testRenderException()
    {
        $this->expectException(\Exception::class);
        $this->tlz->render('nofile.php', []);
    }
}
