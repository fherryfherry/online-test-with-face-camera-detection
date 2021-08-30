<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command("add-question {question}", function() {
    $data = new \App\Models\Question();
    $data->question = $this->argument("question");
    $data->save();
    $this->info("Done");
});

Artisan::command("clear-test", function() {
   \App\Models\ReportTest::query()->delete();
   $this->info("Done");
});

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
