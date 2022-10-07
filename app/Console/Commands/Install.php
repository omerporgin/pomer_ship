<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Artisan;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Exception;

class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $headerCount = 1;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $this->clearLogs();

        $this->createDataBase();

        $this->createSuperAdmin();

        $this->scanForLanguageVariables();

        $this->addCargoZones();

        $this->end();

        return Command::SUCCESS;
    }

    /**
     * @return void
     */
    protected function clearLogs()
    {
        $this->header('Clearing old data');
        $bar = $this->output->createProgressBar(6);// 6 step
        $bar->setFormat("%message%\n \t%current%/%max% [%bar%] %percent:3s%%");
        $bar->setMessage("");
        $bar->start();
        try {
            Artisan::call("storage:link");

            // Clear cache
            Artisan::call("optimize:clear");
            $bar->setMessage("\tCaches have been cleared!");
            $bar->advance();

            Artisan::call("view:clear");
            $bar->setMessage("\tViews have been cleared!");
            $bar->advance();

            Artisan::call("config:clear");
            $bar->setMessage("\tConfig data have been cleared!");
            $bar->advance();

            Artisan::call("event:clear");
            $bar->setMessage("\tEvents have been cleared!");
            $bar->advance();
        } catch (\Exception $e) {
            $this->line('');
            $this->error($e->getMessage());
        }

        // Clear logs
        foreach (glob(storage_path('logs/*')) as $filename) {
            if (\Str::endsWith($filename, '.log')) {
                unlink($filename);
            }
        }
        $bar->advance();
        $bar->setMessage("\tLogs have been cleared!");

        // Clear session files
        foreach (glob("storage/framework/sessions/*") as $file) {
            if (basename($file) != '.gitignore') {
                unlink($file); // delete file
            }
        }
        $bar->setMessage("\tSessions have been cleared!");
        $bar->advance();
        $bar->setMessage("");
        $bar->finish();
        $this->line('');
        $this->info("\tCaches, views, configs, events, sessions, logs cleared!");
    }

    /**
     * @return void
     */
    protected function createDataBase()
    {
        $this->header('Trying to execute migrations.');
        $this->comment("\tCurrent DB will be wiped if you run migrations!");
        if ($this->confirm("\tDo you wish to continue with migrations?")) {
            try {

                Artisan::call('db:wipe');
                $this->comment("\tCurrent DB wiped!");

                // Call migrations one by one
                $MigrationFiles = glob("./database/migrations/*");
                $bar = $this->output->createProgressBar(count($MigrationFiles));
                $bar->setFormat("%message%\n \t%current%/%max% [%bar%] %percent:3s%%");
                $bar->start();
                foreach ($MigrationFiles as $migration) {
                    Artisan::call("migrate:refresh --path=" . $migration);
                    $bar->setMessage("\t" . $migration . ' executed!');
                    $bar->advance();
                }
                $bar->setMessage("");
                $bar->finish();

                $this->line('');
                $this->info("\tMigrations executed!");

                try {
                    $this->header('Trying to seed database.');
                    $fileList = glob("./database/sql/*");

                    $bar = $this->output->createProgressBar(count($fileList));
                    $bar->setFormat("\t" . "%message%\n \t%current%/%max% [%bar%] %percent:3s%%");
                    $bar->start();
                    foreach ($fileList as $path) {
                        @\DB::unprepared(file_get_contents($path));
                        $bar->setMessage($path . ' file seeded!');
                        $bar->advance();
                    }
                    $bar->setMessage("");
                    $bar->finish();
                    $this->line('');
                    $this->info("\tDatabase seeding completed successfully.");

                } catch (\Exception $e) {
                    $errorMsg = substr($e->getMessage(), 0, 200);
                    $this->error($errorMsg);
                }

            } catch (\Exception $e) {
                $this->warn('Error :');
                $this->error($e->getMessage());
            }
        }
    }

    /**
     *
     * @return void
     */
    protected function createSuperAdmin()
    {
        $this->header('Creating super admin.');
        $this->comment("\tWe need your credentals. Your data will not be validated!");

        $askAgain = true;
        while ($askAgain) {
            $email = $this->ask("\tYour email?");
            $password = $this->ask("\tYour password?");
            if ($this->confirm("\tYur credentials " . $email . ":" . $password . " are correct?")) {
                $askAgain = false;
            }
        }

        $user = new \App\Models\User;
        $user->email = $email;
        $user->name = 'name';
        $user->surname = 'surname';
        $user->password = Hash::make($password);
        $user->email_verified_at = date("Y-m-d H:i:s");
        $user->user_type = 0;
        $user->permission_id = 13;
        if ($user->save()) {
            $this->info("\tSuper Admin created...");
        } else {
            $this->error("\tSuper Admin couldnt be created...");
        }
    }

    /**
     * @return void
     */
    protected function scanForLanguageVariables()
    {
        $this->header('Scanning for language variables.');
        $this->comment("\tThis may take several minutes");
        try {
            $localizationService = app()->make(\App\Services\LocalizationService::class);
            $found = $localizationService->scan('./public/');
            $this->info("\t" . $found . " variables found.");
        } catch (\Exception $e) {
            $this->error("\t" . $e->getMessage());
        }
    }

    /**
     * @return void
     */
    protected function end()
    {
        $this->line('');
        $this->line('');
        $this->info("\tYOUR INSTILLATION COMPLETED SUCCESSFULLY...");
        $this->line('');
        $this->comment("\tIf you haven't done yet, you need to run commands below to end installation:");
        $this->comment("\t\tcomposer update");
        $this->comment("\t\tnpm install");
        $this->comment("\t\tnpm run prod");
    }

    /**
     * @return void
     */
    protected function addCargoZones()
    {
        $this->header('Updating cargo zones.');

        $result = \App\Libraries\Shippings\Zones\Zones::updateCargoZones();
        foreach ($result as $item) {
            $this->comment("\t" . $item->name . " is updated. " . count($item->notfoundItems) . " items not found. ");
            if ($item->notfoundItems > 0) {
                $this->line(implode(",", $item->notfoundItems));
            }
        }
    }

    /**
     * Creates header
     *
     * @param $header
     * @return void
     */
    protected function header($header)
    {
        $this->line('');
        $this->line('');
        $this->line('');
        $this->line('STEP ' . $this->headerCount . ' - ' . $header);
        $this->headerCount++;
    }
}
