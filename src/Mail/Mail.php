<?php

namespace Nur\Mail;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

/**
 * Class Mail
 * Adapted from PHPMailer.
 * @see https://github.com/PHPMailer/PHPMailer
 *
 * @package Nur\Mail
 */
class Mail extends PHPMailer
{
    /**
     * SMTP Constants
     */
    const SMTP_DEBUG_OFF = SMTP::DEBUG_OFF;
    const SMTP_DEBUG_CLIENT = SMTP::DEBUG_CLIENT;
    const SMTP_DEBUG_SERVER = SMTP::DEBUG_SERVER;

    /**
     * Mail constructor
     *
     * @param array     $config
     * @param bool|null $exceptions
     *
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function __construct(array $config, $exceptions = null)
    {
        parent::__construct($exceptions);
        $config = json_decode(json_encode($config));

        $this->configure($config);
    }

    /**
     * Set SMTP host
     *
     * @param string $host
     *
     * @return void
     */
    public function setHost(string $host): void
    {
        $this->Host = $host;
    }

    /**
     * Get SMTP host
     *
     * @return string
     */
    public function getHost(): string
    {
        return $this->Host;
    }

    /**
     * Set SMTP port
     *
     * @param integer $port
     *
     * @return void
     */
    public function setPort(int $port): void
    {
        $this->Port = $port;
    }

    /**
     * Get SMTP port
     *
     * @return int
     */
    public function getPort(): int
    {
        return $this->Port;
    }

    /**
     * Set SMTP username
     *
     * @param string $username
     *
     * @return void
     */
    public function setUsername(string $username): void
    {
        $this->Username = $username;
    }

    /**
     * Get SMTP username
     *
     * @return string
     */
    public function getUsername(): string
    {
        return $this->Username;
    }

    /**
     * Set SMTP password
     *
     * @param string $password
     *
     * @return void
     */
    public function setPassword(string $password): void
    {
        $this->Password = $password;
    }

    /**
     * Get SMTP password
     *
     * @return string
     */
    public function getPassword(): string
    {
        return $this->Password;
    }

    /**
     * Set SMTP charset
     *
     * @param string $charset
     *
     * @return void
     */
    public function setCharset(string $charset): void
    {
        $this->CharSet = $charset;
    }

    /**
     * Get SMTP charset
     *
     * @return string
     */
    public function getCharset(): string
    {
        return $this->CharSet;
    }

    /**
     * Set Content Type
     *
     * @param string $contentType
     *
     * @return void
     */
    public function setContentType(string $contentType): void
    {
        $this->ContentType = $contentType;
    }

    /**
     * Get Content Type
     *
     * @return string
     */
    public function getContentType(): string
    {
        return $this->ContentType;
    }

    /**
     * Set mail subject
     *
     * @param string $subject
     *
     * @return void
     */
    public function subject(string $subject): void
    {
        $this->Subject = $subject;
    }

    /**
     * Set body of mail
     *
     * @param string $body
     *
     * @return void
     */
    public function body(string $body): void
    {
        $this->Body = $body;
    }

    /**
     * Set alt body of mail
     *
     * @param string $altBody
     *
     * @return void
     */
    public function altBody(string $altBody): void
    {
        $this->AltBody = $altBody;
    }

    /**
     * Get Mail Error Info
     *
     * @return string
     */
    public function getError(): string
    {
        return $this->ErrorInfo;
    }

    /**
     * Class destruct method
     */
    function __destruct()
    {
        parent::__destruct();
    }

    /**
     * Configure to Mail client
     *
     * @param object $config
     *
     * @return void
     * @throws \PHPMailer\PHPMailer\Exception
     */
    protected function configure($config): void
    {
        if ($config->driver === 'smtp') {
            $this->isSMTP();
            $this->SMTPAuth = true;
            $this->SMTPSecure = $config->encryption === 'tls'
                ? static::ENCRYPTION_STARTTLS
                : static::ENCRYPTION_SMTPS;
            $this->setHost($config->host);
            $this->setPort($config->port);
            $this->setUsername($config->username);
            $this->setPassword($config->password);
        } elseif ($config->driver === 'sendmail') {
            $this->Sendmail = $config->sendmail;
            $this->isSendmail();
        } else {
            $this->isMail();
        }

        // Set mail charset
        $this->setCharset($config->charset);

        // Set mail FROM information
        $this->setFrom($config->from->address, $config->from->name);
    }
}
