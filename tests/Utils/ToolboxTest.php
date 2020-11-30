<?php


namespace App\Tests\Utils;


use App\Utils\Toolbox;
use PHPUnit\Framework\TestCase;

class ToolboxTest extends TestCase
{
    /*public function testGetWordsNumberBasic()
    {
        $toolbox = new Toolbox();
        $text = 'Sit aliquam eum aliquam saepe sunt exercitationem.';

        $this->assertEquals(8, $toolbox->getWordsNumber($text));
    }*/

    public function testCountLink()
    {
        $toolbox = new Toolbox();

        $text = '<a href="#">1</a>';

        $this->assertEquals(2, $toolbox->countLinks($text));

    }

}