<?php

use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

/**
 * The default class to use for all routes
 *
 * The following route classes are supplied with CakePHP and are appropriate
 * to set as the default:
 *
 * - Route
 * - InflectedRoute
 * - DashedRoute
 *
 * If no call is made to `Router::defaultRouteClass()`, the class used is
 * `Route` (`Cake\Routing\Route\Route`)
 *
 * Note that `Route` does not do any inflections on URLs which will result in
 * inconsistently cased URLs when used with `:plugin`, `:controller` and
 * `:action` markers.
 *
 * Cache: Routes are cached to improve performance, check the RoutingMiddleware
 * constructor in your `src/Application.php` file to change this behavior.
 *
 */
Router::defaultRouteClass(DashedRoute::class);

Router::scope('/', function (RouteBuilder $routes) {
    // Register scoped middleware for in scopes.
    //$routes->registerMiddleware('csrf', new \Cake\Http\Middleware\CsrfProtectionMiddleware([
    //    'httpOnly' => true
    //]));

    /**
     * Apply a middleware to the current route scope.
     * Requires middleware to be registered via `Application::routes()` with `registerMiddleware()`
     */
    //$routes->applyMiddleware('csrf');

    $routes->connect('/install/:action', ['controller' => 'Install']);
    $routes->redirect('/install', ['controller' => 'Install', 'action' => 'index']);

    /**
     * Here, we are connecting '/' (base path) to a controller called 'Pages',
     * its action called 'display', and we pass a param to select the view file
     * to use (in this case, src/Template/Pages/home.ctp)...
     */
    $routes->connect('/', ['controller' => 'Pages', 'action' => 'home'], ['_name' => 'home']);

    $routes->connect('/st', ['controller' => 'Tools', 'action' => 'st']);

    $routes->connect('/api', ['controller' => 'Tools', 'action' => 'api']);

    $routes->connect('/full', ['controller' => 'Tools', 'action' => 'full']);

    $routes->connect('/bookmarklet', ['controller' => 'Tools', 'action' => 'bookmarklet']);

    $routes->connect('/payment/ipn', ['controller' => 'Invoices', 'action' => 'ipn']);

    $routes->connect(
        '/pages/:slug',
        ['controller' => 'Pages', 'action' => 'view'],
        ['pass' => ['slug'], '_name' => 'page.view']
    );

    $routes->connect('/blog', ['controller' => 'Posts', 'action' => 'index'], ['_name' => 'blog.index']);

    $routes->connect(
        '/blog/:id-:slug',
        ['controller' => 'Posts', 'action' => 'view'],
        ['pass' => ['id', 'slug'], 'id' => '[0-9]+', '_name' => 'blog.view']
    );

    $routes->connect('/forms/contact', ['controller' => 'Forms', 'action' => 'contact'], ['_name' => 'contact']);

    $routes->connect('/links/shorten', ['controller' => 'Links', 'action' => 'shorten'], ['_name' => 'shorten']);

    $routes->connect('/sitemap', ['controller' => 'Sitemap', 'action' => 'index'], ['_name' => 'sitemap.index']);
    $routes->connect('/sitemap/general', ['controller' => 'Sitemap', 'action' => 'general']);
    $routes->connect('/sitemap/pages', ['controller' => 'Sitemap', 'action' => 'pages']);
    $routes->connect('/sitemap/posts', ['controller' => 'Sitemap', 'action' => 'posts']);
    $routes->connect('/sitemap/links', ['controller' => 'Sitemap', 'action' => 'links']);

    $routes->connect(
        '/bundle/:username/:slug',
        ['controller' => 'bundles', 'action' => 'view'],
        ['pass' => ['username', 'slug'], '_name' => 'bundle.view']
    );

    $routes->connect(
        '/:alias/info',
        ['controller' => 'Statistics', 'action' => 'viewInfo'],
        ['pass' => ['alias'], '_name' => 'short.info']
    );
    $routes->connect(
        '/:alias',
        ['controller' => 'Links', 'action' => 'view'],
        ['pass' => ['alias'], '_name' => 'short']
    );
});

/**
 * Auth routes
 */
Router::prefix('auth', function (RouteBuilder $routes) {
    // All routes here will be prefixed with ‘/auth‘
    // And have the prefix => auth route element added.
    $routes->connect('/signin', ['controller' => 'Users', 'action' => 'signin'], ['_name' => 'signin']);

    $routes->connect('/signup', ['controller' => 'Users', 'action' => 'signup'], ['_name' => 'signup']);

    $routes->connect('/logout', ['controller' => 'Users', 'action' => 'logout'], ['_name' => 'logout']);

    $routes->connect('/forgot-password', ['controller' => 'Users', 'action' => 'forgotPassword']);

    /**
     * Connect catchall routes for all controllers.
     *
     * Using the argument `DashedRoute`, the `fallbacks` method is a shortcut for
     *
     * ```
     * $routes->connect('/:controller', ['action' => 'index'], ['routeClass' => 'DashedRoute']);
     * $routes->connect('/:controller/:action/*', [], ['routeClass' => 'DashedRoute']);
     * ```
     *
     * Any route class can be used with this method, such as:
     * - DashedRoute
     * - InflectedRoute
     * - Route
     * - Or your own route class
     *
     * You can remove these routes once you've connected the
     * routes you want in your application.
     */
    $routes->fallbacks('DashedRoute');
});

/**
 * Member routes
 */
Router::prefix('member', function (RouteBuilder $routes) {
    // All routes here will be prefixed with ‘/member‘
    // And have the prefix => member route element added.
    $routes->connect(
        '/dashboard',
        ['controller' => 'Users', 'action' => 'dashboard'],
        ['_name' => 'member_dashboard']
    );

    /**
     * Connect catchall routes for all controllers.
     *
     * Using the argument `DashedRoute`, the `fallbacks` method is a shortcut for
     *
     * ```
     * $routes->connect('/:controller', ['action' => 'index'], ['routeClass' => 'DashedRoute']);
     * $routes->connect('/:controller/:action/*', [], ['routeClass' => 'DashedRoute']);
     * ```
     *
     * Any route class can be used with this method, such as:
     * - DashedRoute
     * - InflectedRoute
     * - Route
     * - Or your own route class
     *
     * You can remove these routes once you've connected the
     * routes you want in your application.
     */
    $routes->fallbacks(DashedRoute::class);
});

/**
 * Admin routes
 */
Router::prefix('admin', function (RouteBuilder $routes) {
    // All routes here will be prefixed with ‘/admin‘
    // And have the prefix => admin route element added.
    $routes->connect(
        '/dashboard',
        ['controller' => 'Users', 'action' => 'dashboard'],
        ['_name' => 'admin_dashboard']
    );

    $routes->fallbacks(DashedRoute::class);
});

/**
 * If you need a different set of middleware or none at all,
 * open new scope and define routes there.
 *
 * ```
 * Router::scope('/api', function (RouteBuilder $routes) {
 *     // No $routes->applyMiddleware() here.
 *     // Connect API actions here.
 * });
 * ```
 */
