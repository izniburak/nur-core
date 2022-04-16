<?php

namespace Nur\Providers;

use Nur\Kernel\ServiceProvider;
use Illuminate\View\FileViewFinder;
use Illuminate\View\Factory;
use Illuminate\View\DynamicComponent;
use Illuminate\View\Engines\PhpEngine;
use Illuminate\View\Engines\FileEngine;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Compilers\BladeCompiler;

class Blade extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     * @throws
     */
    public function register()
    {
        $this->registerViewFinder();
        $this->registerEngineResolver();
        $this->registerFactory();
        $this->registerBladeEngine($this->app['view.engine.resolver']);
        $this->registerDirectives($this->app['blade.compiler']);
    }

    /**
     * Register the view environment.
     *
     * @return void
     */
    public function registerFactory()
    {
        $this->app->singleton('view', function ($app) {
            // Next we need to grab the engine resolver instance that will be used by the
            // environment. The resolver will be used by an environment to get each of
            // the various engine implementations such as plain PHP or Blade engine.
            $resolver = $app['view.engine.resolver'];
            $finder = $app['view.finder'];
            $env = new Factory($resolver, $finder, $app['events']);
            // We will also set the container instance on this view environment since the
            // view composers may be classes registered in the container, which allows
            // for great testable, flexible composers for the application developer.
            $env->setContainer($app);
            $env->share('app', $app);
            return $env;
        });
    }

    /**
     * Register the view finder implementation.
     *
     * @return void
     */
    public function registerViewFinder()
    {
        $this->app->singleton('view.finder', function ($app) {
            return new FileViewFinder($app['files'], [app_path('Views')]);
        });
    }

    /**
     * Register the engine resolver instance.
     *
     * @return void
     */
    public function registerEngineResolver()
    {
        $this->app->singleton('view.engine.resolver', function () {
            $resolver = new EngineResolver;
            // Next we will register the various engines with the resolver so that the
            // environment can resolve the engines it needs for various views based
            // on the extension of view files. We call a method for each engines.
            foreach (['file', 'php', 'blade'] as $engine) {
                $this->{'register' . ucfirst($engine) . 'Engine'}($resolver);
            }
            return $resolver;
        });
    }

    /**
     * Register the file engine implementation.
     *
     * @param  \Illuminate\View\Engines\EngineResolver $resolver
     *
     * @return void
     */
    public function registerFileEngine($resolver)
    {
        $resolver->register('file', function () {
            return new FileEngine($this->app['files']);
        });
    }

    /**
     * Register the PHP engine implementation.
     *
     * @param  \Illuminate\View\Engines\EngineResolver $resolver
     *
     * @return void
     */
    public function registerPhpEngine($resolver)
    {
        $resolver->register('php', function () {
            return new PhpEngine($this->app['files']);
        });
    }

    /**
     * Register the Blade engine implementation.
     *
     * @param  \Illuminate\View\Engines\EngineResolver $resolver
     *
     * @return void
     */
    public function registerBladeEngine($resolver)
    {
        // The Compiler engine requires an instance of the CompilerInterface, which in
        // this case will be the Blade compiler, so we'll first create the compiler
        // instance to pass into the engine so it can compile the views properly.
        $this->app->singleton('blade.compiler', function ($app) {
            return tap(new BladeCompiler($app['files'], realpath(cache_path('blade'))), function ($blade) {
                $blade->component('dynamic-component', DynamicComponent::class);
            });
        });

        $resolver->register('blade', function () {
            return new CompilerEngine($this->app['blade.compiler'], $this->app['files']);
        });
    }

    /**
     * Add extra directives to the blade templating compiler.
     *
     * @param BladeCompiler $blade The compiler to extend
     *
     * @return void
     */
    public function registerDirectives(BladeCompiler $blade)
    {
        $keywords = [
            "namespace",
            "use",
        ];

        foreach ($keywords as $keyword) {
            $blade->directive($keyword, function ($parameter) use ($keyword) {
                $parameter = trim($parameter, "()");
                return "<?php {$keyword} {$parameter} ?>";
            });
        }

        $assetify = function ($file, $type) {
            $file = trim($file, "()");
            if (in_array(substr($file, 0, 1), ["'", '"'], true)) {
                $file = trim($file, "'\"");
            } else {
                return "{{ {$file} }}";
            }
            if (substr($file, 0, 1) !== "/") {
                $file = "/{$type}/{$file}";
            }
            if (substr($file, (strlen($type) + 1) * -1) !== ".{$type}") {
                $file .= ".{$type}";
            }
            return $file;
        };

        $blade->directive("css", function ($parameter) use ($assetify) {
            $file = $assetify($parameter, "css");
            return '<link rel="stylesheet" type="text/css" href="' . $file . '"/>';
        });

        $blade->directive("js", function ($parameter) use ($assetify) {
            $file = $assetify($parameter, "js");
            return '<script type="text/javascript" src="' . $file . '"></script>';
        });
    }
}
