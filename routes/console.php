<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Schedule;
use App\Console\Commands\MonitorWebsites;

Schedule::command('monitor:websites')->everyFifteenMinutes();

Schedule::command('inspire')->hourly();
