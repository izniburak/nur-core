<?php

namespace Nur\Facades;

use Nur\Kernel\Facade;

/**
 * @method static void setHost(string $host)
 * @method static string getHost()
 * @method static void setPort(int $port)
 * @method static int getPort()
 * @method static void setUsername(string $username)
 * @method static string getUsername()
 * @method static void setPassword(string $password)
 * @method static string getPassword()
 * @method static void setCharset(string $charset)
 * @method static string getCharset()
 * @method static void setContentType(string $contentType)
 * @method static string getContentType()
 * @method static void subject(string $subject)
 * @method static void body(string $body)
 * @method static void altBody(string $altBody)
 * @method static string getError()
 * @method static bool setFrom(string $address, string $name = '', bool $auto = true)
 * @method static bool addAddress(string $address, string $name = '')
 * @method static bool addReplyTo(string $address, string $name = '')
 * @method static bool addCC(string $address, string $name = '')
 * @method static bool addBCC(string $address, string $name = '')
 * @method static bool addAttachment(string $path, string $name = '', string $encoding = 'base64', string $type = '', string $disposition = 'attachment')
 * @method static void isHTML(bool $isHtml = true)
 * @method static string msgHTML($message, $basedir = '', $advanced = false)
 * @method static void clearAddresses()
 * @method static void clearAttachments()
 * @method static void clearCustomHeaders()
 * @method static bool send()
 *
 * @see \Nur\Mail\Mail
 */
class Mail extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'mail';
    }
}
