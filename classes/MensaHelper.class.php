<?php

/**
 * @author  David Siegfried <david.siegfried@uni-vechta.de>
 * @license GPL2 or any later version
 */

class MensaHelper
{
    public static function getMenu($timestamp = null)
    {
        $file = self::getFilename();
        
        if (!file_exists($file)) {
            return [];
        }
        
        $content = array_map(function ($string) {
            $string = utf8_encode($string);
            return str_getcsv($string, "\t");
        }, file($file));
        
        $data     = [];
        $language = substr($_SESSION['_language'], 0, 2);
        
        $headLine = $content[0];
        unset($content[0]);
        $pos = array_change_key_case(array_flip($headLine));
        
        foreach ($content as $row) {
            if ($row[$pos['mensa']] == Config::get()->MENSA_LOCATION) {
                $date  = strtotime($row[$pos['datum']]);
                $order = $row[$pos['speise_bezeichnung']];
                $item  = [
                    'TEXT1'       => $row[$pos['text1']],
                    'TEXT2'       => $row[$pos['text2']],
                    'TEXT3'       => $row[$pos['text3']],
                    'STD_PREIS'   => number_format(floatval(str_replace(',', '.', $row[$pos['std_preis']])), 2, ',', '.'),
                    'BED_PREIS'   => number_format(floatval(str_replace(',', '.', $row[$pos['bed_preis']])), 2, ',', '.'),
                    'GÄSTE_PREIS' => number_format(floatval(str_replace(',', '.', $row[$pos['gäste_preis']])), 2, ',', '.'),
                    'FREI1'       => $row[$pos['frei1']],
                    'ZSNUMMERN'   => $row[$pos['zsnummern']],
                    'ZSNAMEN'     => $row[$pos['zsnamen']],
                ];
                if ($language == 'en') {
                    $item['TEXT1'] = $row[$pos['text1_1']] ?: $item['TEXT1'];
                    $item['TEXT2'] = $row[$pos['text2_1']] ?: $item['TEXT2'];
                    $item['TEXT3'] = $row[$pos['text3_1']] ?: $item['TEXT3'];
                }
                $data[$date][$order][] = $item;
            }
        }
     
        return $data[$timestamp];
    }
    
    public static function getFilename()
    {
        return $GLOBALS['TMP_PATH'] . '/mensa.txt';
    }
    
    public static function replace($string)
    {
        $patterns     = ['/\(/', '/\)/'];
        $replacements = ['<sup>', '</sup>'];
        
        return preg_replace($patterns, $replacements, htmlReady($string));
    }
}