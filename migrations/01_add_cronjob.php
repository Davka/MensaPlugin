<?

/**
 * @author  David Siegfried <david.siegfried@uni-vechta.de>
 * @license GPL2 or any later version
 */

class AddCronjob extends Migration
{
    public function description()
    {
        return 'Fügt den Cronjob zum Herunterladen der Speisepläne hinzu.';
    }
    
    public function up()
    {
        $task_id  = CronjobScheduler::registerTask($this->getCronjobFilename());
        $schedule = CronjobScheduler::schedulePeriodic($task_id, 0, 0, 2);
        
        $schedule->active = true;
        $schedule->store();
    }
    
    public function down()
    {
        $task = reset(CronjobTask::findByClass('MensaCronjob'));
        CronjobScheduler::unregisterTask($task->task_id);
    }
    
    private function getCronjobFilename()
    {
        return str_replace($GLOBALS['STUDIP_BASE_PATH'] . '/', '',
            realpath(__DIR__ . '/../classes/MensaCronjob.class.php'));
    }
}