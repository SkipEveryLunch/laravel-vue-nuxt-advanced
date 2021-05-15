<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;
use App\Models\User;

class UpdateRankingCommand extends Command
{

    protected $signature = 'update:rankings';

    public function handle()
    {
        $ambassadors = User::ambassadors()->get();
        $bar = $this->output->createProgressBar($ambassadors->count());
        $bar->start();
        $ambassadors->each(function(User $ambassador)use($bar){
            Redis::zadd('rankings',(int)$ambassador->revenue,$ambassador->name);
            $bar->advance();
        });
        $bar->finish();
    }
}
