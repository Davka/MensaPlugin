<?

/**
 * Class MensaHelper
 *
 * @author   David Siegfried <david.siegfried@uni-vechta.de>
 * @package  Vec
 * @version  0.8
 * @license  GPL2 or any later version
 */
class MensaHelper
{
    public static function getMenu($timstamp = null)
    {
        $file = self::getFilename();
        
        if (!file_exists($file)) {
            return [];
        }
        $handler = fopen($file, 'r');
        $data    = [];
        if ($handler !== false) {
            $headLine = fgetcsv($handler, 0, '	');
            while (($row = fgetcsv($handler, 0, '	')) !== false) {
                $order[] = $row[5];
                if ($row[0] == Config::get()->MENSA_LOCATION) {
                    $data[strtotime($row[1])][$row[5]][$row[4]][$headLine[6]]  = $row[6];
                    $data[strtotime($row[1])][$row[5]][$row[4]][$headLine[7]]  = $row[7];
                    $data[strtotime($row[1])][$row[5]][$row[4]][$headLine[8]]  = $row[8];
                    $data[strtotime($row[1])][$row[5]][$row[4]][$headLine[10]] = $row[10];
                    $data[strtotime($row[1])][$row[5]][$row[4]][$headLine[11]] = number_format(str_replace(',', '.', $row[11]), 2, ',', '.');
                    $data[strtotime($row[1])][$row[5]][$row[4]][$headLine[12]] = number_format(str_replace(',', '.', $row[12]), 2, ',', '.');
                    $data[strtotime($row[1])][$row[5]][$row[4]][$headLine[13]] = number_format(str_replace(',', '.', $row[13]), 2, ',', '.');
                    $data[strtotime($row[1])][$row[5]][$row[4]][$headLine[14]] = $row[14];
                    $data[strtotime($row[1])][$row[5]][$row[4]][$headLine[15]] = $row[15];
                    $data[strtotime($row[1])][$row[5]][$row[4]][$headLine[16]] = $row[16];
                }
            }
            fclose($handler);
        }
        if (!$timstamp) {
            return $data;
        }
        
        return $data[$timstamp];
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