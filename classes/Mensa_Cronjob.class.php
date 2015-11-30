<?php

class MensaCronjob extends CronJob
{
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
    }

    public function execute($last_result, $parameters = array())
    {
        $targetFile  = 'mensa.txt';
        $sourceFile  = 'ftp://' . Config::get()->MENSA_FTP_SERVER . DIRECTORY_SEPARATOR . Config::get()->MENSA_FTP_FILE;
        // function settings
        $timeout  = 50;
        $fileOpen = 'w';

        $curl = curl_init();
        $file = fopen($GLOBALS['TMP_PATH'] . DIRECTORY_SEPARATOR . $targetFile, $fileOpen);
        curl_setopt($curl, CURLOPT_URL, $sourceFile);
        curl_setopt($curl, CURLOPT_USERPWD, Config::get()->MENSA_FTP_USER . ':' . Config::get()->MENSA_FTP_PASS);

        // curl settings
        curl_setopt($curl, CURLOPT_FAILONERROR, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_FILE, $file);

        $result = curl_exec($curl);;

        curl_close($curl);
        fclose($file);

        return $result;
    }
}