<?php

/**
 * @author  David Siegfried <david.siegfried@uni-vechta.de>
 * @license GPL2 or any later version
 */
class MensaCronjob extends CronJob
{
    private static $curl_timeout = 50;

    public static function getName()
    {
        return _('Mensa-Cronjob');
    }

    public static function getDescription()
    {
        return _('Lädt die Speisepläne für das Studentenwerk Osnabrück herunter');
    }

    public function setUp()
    {
        PluginEngine::getPlugin('MensaPlugin');
    }

    public function execute($last_result, $parameters = [])
    {
        $sourceFile = 'ftp://' . Config::get()->MENSA_FTP_SERVER . '/' . Config::get()->MENSA_FTP_FILE;
        $filename   = MensaHelper::getFilename();
        $curl       = curl_init();

        curl_setopt($curl, CURLOPT_URL, $sourceFile);
        curl_setopt($curl, CURLOPT_USERPWD, Config::get()->MENSA_FTP_USER . ':' . Config::get()->MENSA_FTP_PASS);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, self::$curl_timeout);

        $result = curl_exec($curl);

        if ($result) {
            echo "Der Import der Mensa-Datei war erfolgreich!\n";
        } else {
            echo "Der Import der Mensa-Datei war nicht erfolgreich\n";
        }

        curl_close($curl);

        if ($result !== false) {
            file_put_contents($filename, $result);
        }

        if (!isset($_SERVER)) {
            chown($filename, 'www-data');
            chgrp($filename, 'www-data');
        }
    }
}