<?php

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

        if (CronjobTask::countByClass(MensaCronjob::class)) {
            $task_id = CronjobScheduler::registerTask(new MensaCronjob());
            $schedule = CronjobScheduler::schedulePeriodic($task_id, 0, 0, 2);
            $schedule->active = true;
            $schedule->store();
        }
    }

    public function down()
    {
        $task = CronjobTask::findOneBySQL(MensaCronjob::class);
        if($task) {
            CronjobScheduler::unregisterTask($task->task_id);
        }
    }

}