<?php

use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Foundation\Application;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use App\Http\Middleware\VerifyCsrfToken;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function ($middleware) {
        // Middleware para as rotas web
        $middleware->web(append: [
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
        ]);
        
        // Middleware para as rotas API
     
        // Excluir rotas específicas da validação CSRF (exemplo para 'api/*')
        $middleware->validateCsrfTokens(except: [
            'api/*', // Excluir todas as rotas da API da validação CSRF
            // Adicione outras rotas específicas, se necessário
        ]);
    })
    ->withExceptions(function ($exceptions) {
        //
    })
    ->create();
