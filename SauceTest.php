<?php
// To run this test, install Sausage (see http://github.com/jlipps/sausage-bun
// to get the curl one-liner to run in this directory), then run:
//     vendor/bin/phpunit SauceTest.php

require_once "vendor/autoload.php";
define("APP_URL", "http://appium.s3.amazonaws.com/TestApp6.0.app.zip");

class SauceTest extends Sauce\Sausage\WebDriverTestCase
{
    protected $numValues = [];

    public static $browsers = [
        [
            'browserName' => 'iPhone',
            'seleniumServerRequestsTimeout' => 240,
            'desiredCapabilities' => [
                'platform' => 'Mac 10.8',
                'device' => 'iPhone Simulator',
                'app' => APP_URL,
                'version' => '6.1',
            ]
        ]
    ];

    public function elemsByTag($tag)
    {
        return $this->elements($this->using('tag name')->value($tag));
    }

    protected function populate()
    {
        $elems = $this->elemsByTag('textField');
        foreach ($elems as $elem) {
            $randNum = rand(0, 10);
            $elem->value($randNum);
            $this->numValues[] = $randNum;
        }
    }

    public function testUiComputation()
    {
        $this->populate();
        $buttons = $this->elemsByTag('button');
        
        // Check if there's at least one button
        if (isset($buttons[0])) {
            $buttons[0]->click();
        } else {
            $this->fail('No button found to click');
        }

        $texts = $this->elemsByTag('staticText');
        
        // Check if there's at least one staticText element
        if (isset($texts[0])) {
            $this->assertEquals(array_sum($this->numValues), (int)$texts[0]->text());
        } else {
            $this->fail('No staticText element found to verify the result');
        }
    }
}
