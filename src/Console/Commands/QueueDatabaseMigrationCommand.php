<?php

namespace XeHub\XePlugin\XeCli\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Schema;
use XeHub\XePlugin\XeCli\Traits\RegisterArtisan;

/**
 * Class QueueDatabaseMigrationCommand
 *
 * @package XeHub\XePlugin\XeCli\Console\Commands
 */
class QueueDatabaseMigrationCommand extends Command implements CommandNameInterface
{
    use RegisterArtisan;

    /**
     * @var string
     */
    protected $signature = 'xe_cli:migrate:queueDatabase';

    /**
     * @var string
     */
    protected $description = "Create Queue's Database Table Migration";

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
            $this->createJobsTable();
            $this->info('Create Jobs Table');

            $this->createFailedJobsTable();
            $this->info('Create Failed Jobs Table');

            $this->makeConfigFile();
            $this->info('Make Queue Config File');

            $this->output->success(
                'Success Migrated Queue Database Table'
            );
        } catch (Exception $exception) {
            $this->info($exception->getMessage());
        }
    }

    /**
     * Create Jobs Table
     *
     * @return void
     * @throws Exception
     */
    public function createJobsTable()
    {
        $tableName = 'jobs';

        if (Schema::hasTable($tableName) === true) {
            throw new Exception(
                "Table [$tableName] Already Exists."
            );
        }

        Schema::create(
            $tableName,
            function (Blueprint $table) {
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
            }
        );
    }

    /**
     * Create 'Failed Jobs' Table
     *
     * @return void
     * @throws Exception
     */
    public function createFailedJobsTable()
    {
        $tableName = 'failed_jobs';

        if (Schema::hasTable($tableName)) {
            throw new Exception("Table [$tableName] Already Exists.");
        }

        Schema::create($tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->dateTime('failed_at');

            $table->engine = 'InnoDB';
        });
    }

    /**
     * Make Config File
     *
     * @throws Exception
     */
    private function makeConfigFile()
    {
        $newFilePath = config_path('production') . '/queue.php';
        $stubFilePath = $this->getStubPath() . '/queue_database.stub';

        /**
         * @TODO File System 사용하는 방법에 대한 가이드 문서 추가.
         */
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
     *  Get Stub Directory Paths
     *
     * @return string
     */
    protected function getStubPath()
    {
        return __DIR__ . '/../stubs/queue';
    }

    /**
     * Get Command's Name
     *
     * @return string
     */
    public function commandName(): string
    {
        return 'xe_cli:migrate:queueDatabase';
    }
}
