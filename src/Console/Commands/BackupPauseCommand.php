<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use TobiSchulz\RsyncBackupServer\Models\SourceServer;

class PauseBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:pause
        {?--list : show list of all servers with status}
        {?--pause= : pause specified server}
        {?--resume= : resume paused server}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if ($id = $this->option('pause')) {
            SourceServer::findOrFail($id)->update([
                'is_pause' => true,
            ]);
            $this->info("{$id}: Paused");
        } elseif ($id = $this->option('resume')) {
            SourceServer::findOrFail($id)->update([
                'is_pause' => false,
            ]);
            $this->info("{$id}: Resumed");
        } else { // if ($id = $this->option('list')) {
            $this->info("Showing status for all servers");
            SourceServer::all()->each(function (SourceServer $server) {
                $status = $server->is_paused ? 'Paused' : 'Running';
                $this->info("{$server->id}: $status");
            });
        }

        return 0;
    }
}
