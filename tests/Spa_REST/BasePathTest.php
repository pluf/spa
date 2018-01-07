<?php
/*
 * This file is part of Pluf Framework, a simple PHP Application Framework.
 * Copyright (C) 2010-2020 Phoinex Scholars Co. (http://dpq.co.ir)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\IncompleteTestError;
require_once 'Pluf.php';

/**
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class Spa_REST_BasePathTest extends TestCase
{

    private static $client = null;
    
    private static $user = null;

    /**
     * @beforeClass
     */
    public static function createDataBase()
    {
        Pluf::start(__DIR__.'/../conf/config.php');
        $m = new Pluf_Migration(Pluf::f('installed_apps'));
        $m->install();
        $m->init();
        
        // Test user
        self::$user = new User();
        self::$user->login = 'test';
        self::$user->first_name = 'test';
        self::$user->last_name = 'test';
        self::$user->email = 'toto@example.com';
        self::$user->setPassword('test');
        self::$user->active = true;
        self::$user->administrator = true;
        if (true !== self::$user->create()) {
            throw new Exception();
        }
        
        self::$client = new Test_Client(array(
            array(
                'app' => 'Spa',
                'regex' => '#^/api/spa#',
                'base' => '',
                'sub' => include 'Spa/urls.php'
            ),
            array(
                'app' => 'User',
                'regex' => '#^/api/user#',
                'base' => '',
                'sub' => include 'User/urls.php'
            ),
            array(
                'app' => 'Spa',
                'regex' => '#^#',
                'base' => '',
                'sub' => include 'Spa/urls-app2.php'
            )
        ));
            
        // default spa
        $path = dirname(__FILE__) . '/../resources/testManifest.zip';
        Tenant_Service::setting('spa.default', 'testManifest');
        Spa_Service::installFromFile($path);
    }

    /**
     * @afterClass
     */
    public static function removeDatabses()
    {
        $m = new Pluf_Migration(Pluf::f('installed_apps'));
        $m->unInstall();
    }

    /**
     * @test
     */
    public function getMainFileOfDefaultSpa()
    {
        $response = self::$client->get('/');
        Test_Assert::assertResponseNotNull($response, 'Fail to load main file of default tenant');
        Test_Assert::assertResponseStatusCode($response, 200, 'Result status code is not 200');
        Test_Assert::assertEquals('<html manifest="/"><h1>test_spa</h1></html>', $response->content, 'Base path replacement is not correct');
    }
    
    /**
     * @test
     */
    public function getFakeFileOfDefaultSpa()
    {
        $response = self::$client->get('/alaki/path');
        Test_Assert::assertResponseNotNull($response, 'Fail to load main file of default tenant');
        Test_Assert::assertResponseStatusCode($response, 200, 'Result status code is not 200');
        Test_Assert::assertEquals('<html manifest="/"><h1>test_spa</h1></html>', $response->content, 'Base path replacement is not correct');
    }
    
    /**
     * @test
     */
    public function getMainFileOfSpa()
    {
        $response = self::$client->get('/testManifest/');
        Test_Assert::assertResponseNotNull($response, 'Fail to load main file of default tenant');
        Test_Assert::assertResponseStatusCode($response, 200, 'Result status code is not 200');
        Test_Assert::assertEquals('<html manifest="/testManifest/"><h1>test_spa</h1></html>', $response->content, 'Base path replacement is not correct');
    }
    
    /**
     * @test
     */
    public function getFakeFileOfSpa()
    {
        $response = self::$client->get('/testManifest/alaki/path');
        Test_Assert::assertResponseNotNull($response, 'Fail to load main file of default tenant');
        Test_Assert::assertResponseStatusCode($response, 200, 'Result status code is not 200');
        Test_Assert::assertEquals('<html manifest="/testManifest/"><h1>test_spa</h1></html>', $response->content, 'Base path replacement is not correct');
    }
    
}



