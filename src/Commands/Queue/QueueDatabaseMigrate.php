<?php

namespace XeHub\XePlugin\XeCli\Commands\Queue;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Schema;
use XeHub\XePlugin\XeCli\Traits\RegisterArtisan;

final class QueueDatabaseMigrate extends Command
{
    use RegisterArtisan;

    protected $signature = 'migrate:queue-database';

    protected $description = 'Database 를 이용해 Queue 처리를 진행하기 위해 테이블과 파일을 생성합니다.';

    /**
     * Session Database Make Command.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Session 테이블과 그에 대한 파일을 생성해줍니다.
     *
     * @return void
     * @throws Exception
     */
    public function handle()
    {
        try {
            $this->makeConfigFile();
            $this->migrateJobsTable();
            $this->migrateFailedJobsTable();

            $this->info('migrate queue table');
        }

        catch (Exception $exception) {
            $this->info($exception->getMessage());
        }
    }

    /**
     * `Job`을 저장할 테이블을 추가합니다.
     *
     * @throws Exception
     */
    public function migrateJobsTable()
    {
        $tableName = "jobs";

        if (Schema::hasTable($tableName)) {
            throw new Exception("Table [$tableName] already exists.");
        }

        Schema::create($tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id', 36)->comment('sender');
            $table->string('queue', 255);
            $table->longText('payload');
            $table->unsignedTinyInteger('attempts');
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
            $table->index(['queue']);

            $table->engine = 'InnoDB';
        });
    }

    /**
     * 실패한 `Job`을 저장할 테이블을 추가합니다.
     *
     * @throws Exception
     */
    public function migrateFailedJobsTable()
    {
        $tableName = "failed_jobs";

        if (Schema::hasTable($tableName)) {
            throw new Exception("Table [$tableName] already exists.");
        }

        $this->schema()->create('failed_jobs', function (Blueprint $table) {
            $table->increments('id');
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->dateTime('failed_at');

            $table->engine = "InnoDB";
        });
    }

    /**
     * 설정 파일을 생성합니다.
     *
     * @throws Exception
     */
    private function makeConfigFile()
    {
        $configFile = sprintf("%s/queue.php", config_path('production'));
        $configStubFile = sprintf("%s/stubs/database.stub", __DIR__);

        if (app(Filesystem::class)->isFile($configFile)) {
            throw new Exception("Destination path [$configFile] already exists.");
        }

        if (!app(Filesystem::class)->copy($configStubFile, $configFile)) {
            throw new Exception("Unable to create file[$configFile]. please check permission.");
        }
    }
}
