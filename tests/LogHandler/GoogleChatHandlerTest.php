<?php

namespace GoogleChatConnector\Tests\LogHandler;

use GoogleChatConnector\GoogleChatLogHandler;
use Monolog\Level;
use Monolog\LogRecord;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\TestCase;

class GoogleChatHandlerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Definir funciones dummy para simular Laravel si no están definidas.
        if (!function_exists('config')) {
            function config($key)
            {
                $config = [
                    'logging.channels.google-chat.url' => 'https://fake.webhook.url',
                    'app.name' => 'TestApp',
                    'app.env' => 'testing',
                    'logging.channels.google-chat.notify_users.default' => 'all',
                    'logging.channels.google-chat.notify_users.error' => '',
                    // Agregar otros valores según sea necesario
                ];
                return $config[$key] ?? null;
            }
        }

        if (!function_exists('request')) {
            function request()
            {
                return new class {
                    public function url()
                    {
                        return 'http://localhost';
                    }
                };
            }
        }
    }

    public function testWriteSendsPostRequest()
    {
        // Fakea las peticiones HTTP
        Http::fake();

        // Crea una instancia de LogRecord para la prueba.
        // Nota: Ajusta la creación de LogRecord según la versión de Monolog.
        $record = new LogRecord(
            new \DateTimeImmutable(),
            'TestApp',
            Level::Alert,
            'Test error message',
            [] // contexto adicional
        );

        $handler = new GoogleChatLogHandler();

        // Accede al método protegido "write" mediante Reflection.
        $reflection = new \ReflectionClass($handler);
        $method = $reflection->getMethod('write');
        $method->setAccessible(true);
        $method->invoke($handler, $record);

        // Verifica que se haya hecho la llamada HTTP al webhook simulado.
        Http::assertSent(function ($request) use ($record) {
            // Comprueba que la URL sea la configurada y que el cuerpo incluya el mensaje.
            $body = $request->body();
            return $request->url() === 'https://fake.webhook.url'
                && strpos($body['text'], 'Test error message') !== false;
        });
    }
}