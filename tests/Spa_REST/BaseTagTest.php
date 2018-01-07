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
class Spa_REST_BaseTagTest extends TestCase
{

    private static $client = null;
    
    private static $user = null;

    /**
     * @beforeClass
     */
    public static function createDataBase()
    {
        Pluf::start(array(
            'general_domain' => 'localhost',
            'general_admin_email' => array(
                'root@localhost'
            ),
            'general_from_email' => 'test@localhost',
            'middleware_classes' => array(
                'Pluf_Middleware_Session'
            ),
            'debug' => true,
            'test_unit' => true,
            'languages' => array(
                'fa',
                'en'
            ),
            'tmp_folder' => dirname(__FILE__) . '/../tmp',
            'template_folders' => array(
                dirname(__FILE__) . '/../templates'
            ),
            'upload_path' => dirname(__FILE__) . '/../tmp',
            'template_tags' => array(),
            'time_zone' => 'Asia/Tehran',
            'encoding' => 'UTF-8',
            
            'secret_key' => '5a8d7e0f2aad8bdab8f6eef725412850',
            'user_signup_active' => true,
            'user_avatra_max_size' => 2097152,
            'db_engine' => 'MySQL',
            'db_version' => '5.5.33',
            'db_login' => 'root',
            'db_password' => '',
            'db_server' => 'localhost',
            'db_database' => 'test',
            'db_table_prefix' => '_test_basetag_rest_',
            
            'mail_backend' => 'mail',
            'user_avatar_default' => dirname(__FILE__) . '/../conf/avatar.svg'
        ));
        $m = new Pluf_Migration(array(
            'Pluf',
            'User',
            'Spa'
        ));
        $m->install();
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
        $path = dirname(__FILE__) . '/../resources/testDefault.zip';
        Tenant_Service::setSetting('spa.default', 'testDefault');
        Spa_Service::installFromFile($path);
        $path = dirname(__FILE__) . '/../resources/testResource.zip';
        Spa_Service::installFromFile($path);
    }

    /**
     * @afterClass
     */
    public static function removeDatabses()
    {
        $m = new Pluf_Migration(array(
            'Pluf',
            'User',
            'Spa'
        ));
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
        Test_Assert::assertEquals('<base href="/">', $response->content, 'Base tag replacement is not correct');
    }
    
    /**
     * @test
     */
    public function getFakeFileOfDefaultSpa()
    {
        $response = self::$client->get('/alaki/path');
        Test_Assert::assertResponseNotNull($response, 'Fail to load main file of default tenant');
        Test_Assert::assertResponseStatusCode($response, 200, 'Result status code is not 200');
        Test_Assert::assertEquals('<base href="/">', $response->content, 'Base tag replacement is not correct');
    }
    
    /**
     * @test
     */
    public function getMainFileOfSpa()
    {
        $response = self::$client->get('/testDefault/');
        Test_Assert::assertResponseNotNull($response, 'Fail to load main file of default tenant');
        Test_Assert::assertResponseStatusCode($response, 200, 'Result status code is not 200');
        Test_Assert::assertEquals('<base href="/testDefault/">', $response->content, 'Base tag replacement is not correct');
    }
    
    /**
     * @test
     */
    public function getFakeFileOfSpa()
    {
        $response = self::$client->get('/testDefault/alaki/path');
        Test_Assert::assertResponseNotNull($response, 'Fail to load main file of default tenant');
        Test_Assert::assertResponseStatusCode($response, 200, 'Result status code is not 200');
        Test_Assert::assertEquals('<base href="/testDefault/">', $response->content, 'Base tag replacement is not correct');
    }
    
}



