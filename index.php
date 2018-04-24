<?php

require_once './vendor/autoload.php';

// Parse the crontab
$tz = new \DateTimeZone('Europe/Kiev');
$parser = new Phonycron\Parser($tz);
$jobs = $parser->parse(file_get_contents(__DIR__.'/crontab'));

$array_moth_names = [1 => 'jan', 2 =>'feb', 3 =>'mar', 4 =>'apr', 5 =>'may', 6 =>'jun', 7 =>'jul', 8 =>'aug', 9 =>'sep', 10 =>'oct', 11 =>'now', 12 =>'dec'];
$number_month = array_keys($array_moth_names);

$array_day_names = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'];
$number_days = array_keys($array_day_names);

$table_jobs = [];
foreach ($jobs as $key => $job) {
    $cron_time_string = str_replace($job->command,'',$job->raw);
    $cron_time_string = str_ireplace($array_moth_names, $number_month, $cron_time_string);
    $cron_time_string = str_ireplace($array_day_names, $number_days, $cron_time_string);

    $cron = Cron\CronExpression::factory($cron_time_string);
    $first_start = $cron->getNextRunDate('2018-04-24 00:00:00')->format('Y-m-d H:i:s');

    $table_job['key'] = $key;
    $table_job['raw'] = $job->raw;
    $table_job['first_start'] = $first_start;

    $table_jobs []=$table_job;
}
?>
<script src="sorttable.js"></script>
<style>
    /* Sortable tables */
    table.sortable thead {
        background-color:#eee;
        color:#666666;
        font-weight: bold;
        cursor: default;
    }
</style>
<table class="sortable" border="1">
    <tr>
        <td>Номер</td>
        <td>Команда</td>
        <td>Время старта</td>
    </tr>
<?php  foreach($table_jobs as $table_job):?>
    <tr>
        <td><?=$table_job['key'];?></td>
        <td><?=$table_job['raw'];?></td>
        <td><?=$table_job['first_start'];?></td>
    </tr>
<?php endforeach;?>
</table>

