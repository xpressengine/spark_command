<?php

namespace XeHub\XePlugin\XeCli\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Schema;
use XeHub\XePlugin\XeCli\Traits\RegisterArtisan;

/**
 * Class MigrateSessionDatabaseCommend
 *
 * @package XeHub\XePlugin\XeCli\Console\Commands
 */
class SessionDatabaseMigrationCommend extends Command implements CommandNameInterface
{
    use RegisterArtisan;

    /**
     * @var string
     */
    protected $signature = 'xe_cli:migrate:sessionDatabase';

    /**
     * @var string
     */
    protected $description = "Create Session's Database Tables";

    /**
     * @var Filesystem
     */
    protected $fileSystem;

    /**
     * __construct
     *
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->fileSystem = $filesystem;
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
            $this->createSessionsTable();
            $this->info('Create Sessions Table');

            $this->makeConfigFile();
            $this->info('Make Session Config File');

            $this->output->success(
                'Success Migrated Session Database Table'
            );
        } catch (Exception $exception) {
            $this->info($exception->getMessage());
        }
    }

    /**
     * Create Sessions Tables
     *
     * @throws Exception
     */
    public function createSessionsTable()
    {
        $tableName = 'sessions';

        if (Schema::hasTable($tableName) === true) {
            throw new Exception(
                "Table [$tableName] Already Exists."
            );
        }

        Schema::create(
            $tableName,
            function (Blueprint $table) {
                $table->string('id', 255)->primary();
                $table->string('user_id', 36)->nullable()->default(null);
                $table->string('ip_address', 45)->nullable()->default(null);
                $table->text('user_agent');
                $table->text('payload');
                $table->integer('last_activity');

                $table->engine = 'InnoDB';
            }
        );
    }

    /**
     * Make Config File
     *
     * @throws Exception
     */
    private function makeConfigFile()
    {
        $newFilePath = config_path('production') . '/session.php';
        $stubFilePath = $this->getStubPath() . '/session_database.stub';

        if ($this->fileSystem->isFile($newFilePath) === true) {
            throw new Exception(
                "Destination path [$newFilePath] already exists."
            );
        }

        if ($this->fileSystem->copy($stubFilePath, $newFilePath) === false) {
            throw new Exception(
                "Unable to create file[$newFilePath]. please check permission."
            );
        }
    }

    /**
     * Get Stub Directory Paths
     *
     * @return string
     */
    protected function getStubPath(): string
    {
        return __DIR__ . '/../stubs/session';
    }

    /**
     * Get Command's Name
     *
     * @return string
     */
    public function commandName(): string
    {
        return 'xe_cli:migrate:sessionDatabase';
    }
}
