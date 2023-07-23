<?php

use PHPUnit\Framework\TestCase;

class CommonControllerTest extends TestCase
{
    protected CommonController $controller;

    public function setUp(): void
    {

        $this->controller = new CommonController();

    }


    /**
     * @covers CommonController::estaLogueado
     * @return void
     */
    public function testEsAdmin(): void
    {
        $this->assertTrue($this->controller->esAdmin());
        $this->assertNotNull($_SESSION["rol"]);
    }

    /**
     * @covers CommonController::estaLogueado
     * @return void
     */
    public function testEstaLogueado(): void
    {
        $logueado = $this->controller->estaLogueado();
        $this->assertIsBool($logueado);
        $this->assertTrue($logueado);
    }
}