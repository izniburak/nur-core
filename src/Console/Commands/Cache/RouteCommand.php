<?php

namespace Nur\Console\Commands\Cache;

use Nur\Console\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class RouteCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('cache:route')
            ->setDescription("Cache your application routes.");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $cacheFile = cache_path('routes.php');
            if (file_exists($cacheFile)) {
                $helper = $this->getHelper('question');
                $question = new ConfirmationQuestion('Old cache will be deleted and re-created. Are you sure? [y/N] : ',
                    false);

                if (!$helper->ask($input, $output, $question)) {
                    return 0;
                }
                unlink($cacheFile);
                $output->writeln('<info>+Success!</info> Routes cache has been deleted.');
                $output->writeln('Routes cache re-creating....');
            }

            require_once app_path('routes.php');
            app('route')->cache();
            $this->updateCacheFile();
        } catch (\Exception $e) {
            throw new \RuntimeException(sprintf($e->getMessage()));
        }

        $output->writeln('<info>+Success!</info> Routes have been cached.');

        return 1;
    }

    protected function updateCacheFile()
    {
        $cacheFile = cache_path('routes.php');
        $cacheContent = file_get_contents($cacheFile);
        if (false === file_put_contents($cacheFile, str_replace(
                "'route' => '.", "'route' => '" . app()->baseFolder(), $cacheContent
            ))) {
            throw new \RuntimeException(sprintf('Config cache file could not be written.'));
        }
    }
}