<?php
/**
 * Class MensaCrojob
 *
 * @author   David Siegfried <david.siegfried@uni-vechta.de>
 * @package  Vec
 * @version  0.8
 * @license  GPL2 or any later version
 */
class MensaCronjob extends CronJob
{
    private static $curl_timeout = 40;
    
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
        \PluginEngine::getPlugin('MensaPlugin');
    }
    
    public function execute($last_result, $parameters = [])
    {
        $sourceFile = 'ftp://' . Config::get()->MENSA_FTP_SERVER . '/' . Config::get()->MENSA_FTP_FILE;
        $filename   = MensaHelper::getFilename();
        $curl       = curl_init();
        $file       = fopen($filename, 'w');
        
        curl_setopt($curl, CURLOPT_URL, $sourceFile);
        curl_setopt($curl, CURLOPT_USERPWD, Config::get()->MENSA_FTP_USER . ':' . Config::get()->MENSA_FTP_PASS);
        curl_setopt($curl, CURLOPT_FAILONERROR, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, self::$curl_timeout);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_FILE, $file);
        
        if (curl_exec($curl)) {
            echo "Der Import der Mensa-Datei war nicht erfolgreich!\n";
        } else {
            echo "Der Import der Mensa-Datei war erfolgreich\n";
        }
        
        curl_close($curl);
        fclose($file);
        
        if (!isset($_SERVER)) {
            chown($filename, 'www-data');
            chgrp($filename, 'www-data');
        }
    }
}