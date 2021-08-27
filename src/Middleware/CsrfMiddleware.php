<?php

namespace App\Middleware;

use Cake\Http\Middleware\CsrfProtectionMiddleware;
use Cake\Http\Response;
use Cake\Http\ServerRequest;

class CsrfMiddleware
{
    public function __invoke(ServerRequest $request, Response $response, callable $next)
    {
        $controller = $request->getParam('controller');
        $action = $request->getParam('action');

        $csrfProtection = true;

        if ($controller === 'Install') {
            $csrfProtection = false;
        }

        if (($controller === 'Invoices') && ($action === 'ipn')) {
            $csrfProtection = false;
        }

        if (($controller === 'Users') && ($action === 'multidomainsAuth')) {
            $csrfProtection = false;
        }

        if ($csrfProtection) {
            $csrf = new CsrfProtectionMiddleware([
                'httpOnly' => true,
            ]);

            // This will invoke the CSRF middleware's `__invoke()` handler,
            // just like it would when being registered via `add()`.
            return $csrf($request, $response, $next);
        }

        return $next($request, $response);
    }
}
