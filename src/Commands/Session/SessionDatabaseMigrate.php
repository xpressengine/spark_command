<?php

namespace XeHub\XePlugin\XeCli\Commands\Session;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use XeHub\XePlugin\XeCli\Traits\RegisterArtisan;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

final class SessionDatabaseMigrate extends Command
{
    use RegisterArtisan;

    protected $signature = 'migrate:session-database';

    protected $description = 'Database 를 이용해 세션 처리를 진행하기 위해 테이블과 파일을 생성합니다.';

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
            $this->migrateTable();

            $this->info('migrate session table');
        }

        catch (Exception $exception) {
            $this->info($exception->getMessage());
        }
    }

    /**
     * 세션을 저장할 테이블을 추가합니다.
     *
     * @throws Exception
     */
    public function migrateTable()
    {
        $tableName = "sessions";

        if (Schema::hasTable($tableName)) {
            throw new Exception("Table [$tableName] already exists.");
        }

        Schema::create($tableName, function (Blueprint $table) {
            $table->string('id', 255)->primary();
            $table->string('user_id', 36)->nullable()->default(null);
            $table->string('ip_address', 45)->nullable()->default(null);
            $table->text('user_agent');
            $table->text('payload');
            $table->integer('last_activity');

            $table->engine = 'InnoDB';
        });
    }

    /**
     * 설정 파일을 생성합니다.
     *
     * @throws Exception
     */
    private function makeConfigFile()
    {
        $configFile = sprintf("%s/session.php", config_path('production'));
        $configStubFile = sprintf("%s/stubs/database.stub", __DIR__);

        if (app(Filesystem::class)->isFile($configFile)) {
            throw new Exception("Destination path [$configFile] already exists.");
        }

        if (!app(Filesystem::class)->copy($configStubFile, $configFile)) {
            throw new Exception("Unable to create file[$configFile]. please check permission.");
        }
    }
}
