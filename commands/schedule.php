<?php
/**
 * https://yiigist.com/package/omnilight/yii2-scheduling#?tab=readme
 * https://www.digitalocean.com/community/tutorials/how-to-use-cron-to-automate-tasks-on-a-vps
 * @var \omnilight\scheduling\Schedule $schedule
 *
 *  * To check correct alias value   \Yii::getAlias('@app')
 */
//-- Setup crontab on linux server, which will run it every minute
// * * * * * php /var/www/html/publiccamera/yii schedule/run --scheduleFile=@app/commands/schedule.php 1>> /dev/null 2>&1
//-- Test on Windows
// php d:\GoogleDrive\Sites\publiccamera\yii schedule/run --scheduleFile=@app/commands/schedule.php

//-- Place here all of your cron jobs

//-- Calculate current Crowd Index for Floor -- Intra-day

//$schedule->call(
//    function(\yii\console\Application $app){
//        $app->runAction('crowd-cron/node-crowd-average');
//    }
//)->everyNMinutes(3);

//$schedule->call(
//    function(\yii\console\Application $app){
//        $app->runAction('crowd-cron/floor-crowd-today');
//    }
//)->everyFiveMinutes();
//
////-- Calculate historical Crowd Index for Floor -- Daily
//
//$schedule->call(
//    function(\yii\console\Application $app){
//        $app->runAction('crowd-cron/floor-crowd-weekly');
//    }
//)->dailyAt('20:00');
//
//$schedule->call(
//    function(\yii\console\Application $app){
//        $app->runAction('crowd-cron/floor-crowd-monthly');
//    }
//)->dailyAt('20:15');
//
//$schedule->call(
//    function(\yii\console\Application $app){
//        $app->runAction('crowd-cron/floor-crowd-weekdays');
//    }
//)->dailyAt('20:30');

//-- Clean up NodeFile table -- Daily

$schedule->call(
    function(\yii\console\Application $app){
        $app->runAction('crowd-cron/node-file-keep-latest-n-each');
    }
)->dailyAt('20:30');

//-- This command will execute python command every five minutes
//$py_file = '';
//$py_command = "python $py_file";
//$schedule->exec($py_command)->everyFiveMinutes();

