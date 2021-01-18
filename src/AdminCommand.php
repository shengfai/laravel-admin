<?php
namespace Shengfai\LaravelAdmin;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class AdminCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the Laravel Admin.';

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
     * @return mixed
     */
    public function handle()
    {
        $this->info('Publishing the assets');
        
        $this->call('vendor:publish', [
            '--provider' => 'Spatie\Permission\PermissionServiceProvider',
            '--force' => true
        ]);
        $this->call('vendor:publish', [
            '--provider' => 'Spatie\Activitylog\ActivitylogServiceProvider',
            '--tag' => 'migrations'
        ]);
        $this->call('vendor:publish', [
            '--provider' => 'Overtrue\LaravelOptions\OptionsServiceProvider',
            '--force' => true
        ]);
        $this->call('vendor:publish', [
            '--provider' => 'Overtrue\LaravelUploader\UploadServiceProvider',
            '--force' => true
        ]);
        $this->call('vendor:publish', [
            '--provider' => 'Shengfai\LaravelAdmin\AdminServiceProvider',
            '--force' => true
        ]);
        $this->call('vendor:publish', [
            '--provider' => 'Spatie\Tags\TagsServiceProvider',
            '--force' => true
        ]);
        
        $this->info('Dumping the composer autoload');
        (new Process([
            'composer dump-autoload'
        ]))->run();
        
        $this->info('Migrating the database tables into your application');
        
        $this->call('notifications:table');
        $this->call('migrate');
    }
}
