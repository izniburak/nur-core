<?php

namespace Nur\Console\Commands\App;

use Nur\Console\Command;
use Nur\Encryption\Encrypter;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class KeygenCommand extends Command
{
    /**
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('app:keygen')
            ->setDescription("Set the application key.")
            ->setHelp("This command makes you to create or re-create application key.")
            ->addOption('--show', '-s', InputOption::VALUE_OPTIONAL, 'Display the key instead of modifying files')
            ->addOption('--jwt', '-jwt', InputOption::VALUE_OPTIONAL,
                'Generate JWT Secret Key for JWT Authentication.');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->hasParameterOption('--jwt') !== false) {
            return $this->jwtKeyGenerator($input, $output);
        }

        $this->appKeyGenerator($input, $output);

        return 0;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function appKeyGenerator(InputInterface $input, OutputInterface $output)
    {
        if ($input->hasParameterOption('--show') !== false) {
            return $output->writeln('<comment>' . config('app.key') . '</comment>');
        }

        $key = $this->generateRandomKey();

        // Next, we will replace the application key in the environment file so it is
        // automatically setup for this developer. This key gets generated using a
        // secure random byte generator and is later base64 encoded for storage.
        if (!$this->setKeyInEnvironmentFile('APP_KEY', $key, config('app.key'), $input, $output)) {
            return;
        }

        config()->set('app.key', $key);
        $output->writeln("<info>+Success!</info> Application key set successfully. [{$key}]");
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function jwtKeyGenerator(InputInterface $input, OutputInterface $output)
    {
        if ($input->hasParameterOption('--show') !== false) {
            $output->writeln('<comment>' . config('auth.jwt.secret') . '</comment>');
            return 0;
        }

        $key = $this->generateRandomKey();
        $key = substr($key, 7, strlen($key));

        // Next, we will replace the application key in the environment file so it is
        // automatically setup for this developer. This key gets generated using a
        // secure random byte generator and is later base64 encoded for storage.
        if (!$this->setKeyInEnvironmentFile('JWT_SECRET', $key, config('auth.jwt.secret'), $input, $output)) {
            return 1;
        }

        config()->set('auth.jwt.key', $key);
        $output->writeln("<info>+Success!</info> JWT secret key set successfully. [{$key}]");

        return 0;
    }

    /**
     * Generate a random key for the application.
     *
     * @return string
     * @throws
     */
    protected function generateRandomKey()
    {
        return 'base64:' . base64_encode(Encrypter::generateKey(config('app.cipher')));
    }

    /**
     * Set the application key in the environment file.
     *
     * @param string          $key
     * @param string          $value
     * @param string          $currentValue
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return bool
     */
    protected function setKeyInEnvironmentFile(string $key, string $value, string $currentValue, $input, $output)
    {
        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion("{$key} key will re-generate. Are you sure?: ", false);
        if (strlen($currentValue) !== 0 && (!$helper->ask($input, $output, $question))) {
            return false;
        }

        $this->writeNewEnvironmentFileWith($key, $value, $currentValue);
        return true;
    }

    /**
     * Write a new environment file with the given key.
     *
     * @param string $key
     * @param string $value
     * @param string $currentValue
     *
     * @return void
     */
    protected function writeNewEnvironmentFileWith(string $key, string $value, string $currentValue)
    {
        $envContent = file_get_contents(base_path('.env'));
        if (strpos($envContent, $key . '=') !== false) {
            file_put_contents(base_path('.env'), preg_replace(
                    $this->keyReplacementPattern($key, $currentValue), $key . '=' . $value, $envContent)
            );
        } else {
            file_put_contents(base_path('.env'), $envContent . PHP_EOL . $key . '=' . $value);
        }
    }

    /**
     * Get a regex pattern that will match env APP_KEY with any random key.
     *
     * @param string $key
     * @param string $value
     *
     * @return string
     */
    protected function keyReplacementPattern($key, $value)
    {
        $escaped = preg_quote('=' . $value, '/');
        return "/^{$key}{$escaped}/m";
    }
}
