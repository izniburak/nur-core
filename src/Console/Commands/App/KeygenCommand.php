<?php

namespace Nur\Console\Commands\App;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Nur\Encryption\Encrypter;

class KeygenCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('app:keygen')
            ->setDescription("Set the application key.")
            ->setHelp("This command makes you to create or re-create application key.")
            ->addOption('--show', '-s', InputOption::VALUE_OPTIONAL, 'Display the key instead of modifying files');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $key = $this->generateRandomKey();
        if ($input->hasParameterOption('--show') !== false) {
            return $output->writeln('<comment>'.$key.'</comment>');
        }

        // Next, we will replace the application key in the environment file so it is
        // automatically setup for this developer. This key gets generated using a
        // secure random byte generator and is later base64 encoded for storage.
        if (! $this->setKeyInEnvironmentFile($key, $input, $output)) {
            return;
        }

        config()->set('app.key', $key);
        $output->writeln('<info>+Success!</info> Application key set successfully.');
    }

    /**
     * Generate a random key for the application.
     *
     * @return string
     */
    protected function generateRandomKey()
    {
        return 'base64:'.base64_encode(
            Encrypter::generateKey(config('app.cipher'))
        );
    }

    /**
     * Set the application key in the environment file.
     *
     * @param  string  $key
     * @return bool
     */
    protected function setKeyInEnvironmentFile($key, $input, $output)
    {
        $currentKey = config('app.key');

        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion('Application key will re-generate. Are you sure?: ', false);
        if (strlen($currentKey) !== 0 && (! $helper->ask($input, $output, $question))) {
            return false;
        }

        $this->writeNewEnvironmentFileWith($key);
        return true;
    }

    /**
     * Write a new environment file with the given key.
     *
     * @param  string  $key
     * @return void
     */
    protected function writeNewEnvironmentFileWith($key)
    {
        file_put_contents(base_path('.env'), preg_replace(
            $this->keyReplacementPattern(),
            'APP_KEY='.$key,
            file_get_contents(base_path('.env'))
        ));
    }

    /**
     * Get a regex pattern that will match env APP_KEY with any random key.
     *
     * @return string
     */
    protected function keyReplacementPattern()
    {
        $escaped = preg_quote('='.config('app.key'), '/');
        return "/^APP_KEY{$escaped}/m";
    }
}
