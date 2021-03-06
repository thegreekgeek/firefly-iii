<?php
/**
 * ImportControllerTest.php
 * Copyright (C) 2016 thegrumpydictator@gmail.com
 *
 * This software may be modified and distributed under the terms of the
 * Creative Commons Attribution-ShareAlike 4.0 International License.
 *
 * See the LICENSE file for details.
 */
use FireflyIII\Import\Setup\CsvSetup;
use Illuminate\Http\UploadedFile;
use FireflyIII\Import\ImportProcedureInterface;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-12-10 at 05:51:41.
 */
class ImportControllerTest extends TestCase
{


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * @covers \FireflyIII\Http\Controllers\ImportController::complete
     */
    public function testComplete()
    {
        $this->be($this->user());
        $this->call('GET', route('import.complete', ['complete']));
        $this->assertResponseStatus(200);
        $this->see('<ol class="breadcrumb">');
    }

    /**
     * @covers \FireflyIII\Http\Controllers\ImportController::configure
     */
    public function testConfigure()
    {
        $this->be($this->user());
        $this->call('GET', route('import.configure', ['configure']));
        $this->assertResponseStatus(200);
        $this->see('<ol class="breadcrumb">');
    }

    /**
     * @covers \FireflyIII\Http\Controllers\ImportController::download
     */
    public function testDownload()
    {
        $this->be($this->user());
        $this->call('GET', route('import.download', ['configure']));
        $this->assertResponseStatus(200);
        $this->see('[]');
    }

    /**
     * @covers \FireflyIII\Http\Controllers\ImportController::finished
     */
    public function testFinished()
    {
        $this->be($this->user());
        $this->call('GET', route('import.finished', ['finished']));
        $this->assertResponseStatus(200);
        $this->see('<ol class="breadcrumb">');
    }

    /**
     * @covers \FireflyIII\Http\Controllers\ImportController::index
     */
    public function testIndex()
    {
        $this->be($this->user());
        $this->call('GET', route('import.index'));
        $this->assertResponseStatus(200);
    }

    /**
     * @covers \FireflyIII\Http\Controllers\ImportController::json
     */
    public function testJson()
    {
        $this->be($this->user());
        $this->call('GET', route('import.json', ['configure']));
        $this->assertResponseStatus(200);
    }

    /**
     * @covers \FireflyIII\Http\Controllers\ImportController::postConfigure
     */
    public function testPostConfigure()
    {
        $importer = $this->mock(CsvSetup::class);
        $importer->shouldReceive('setJob')->once();
        $importer->shouldReceive('saveImportConfiguration')->once();

        $data = [];
        $this->be($this->user());
        $this->call('post', route('import.process-configuration', ['p-configure']), $data);
        $this->assertResponseStatus(302);
        $this->assertRedirectedToRoute('import.settings', ['p-configure']);
    }

    /**
     * @covers \FireflyIII\Http\Controllers\ImportController::postSettings
     */
    public function testPostSettings()
    {
        $importer = $this->mock(CsvSetup::class);
        $importer->shouldReceive('setJob')->once();
        $importer->shouldReceive('storeSettings')->once();
        $data = [];
        $this->be($this->user());
        $this->call('post', route('import.post-settings', ['p-settings']), $data);
        $this->assertResponseStatus(302);
        $this->assertRedirectedToRoute('import.settings', ['p-settings']);
    }

    /**
     * @covers \FireflyIII\Http\Controllers\ImportController::settings
     */
    public function testSettings()
    {
        $importer = $this->mock(CsvSetup::class);
        $importer->shouldReceive('setJob')->once();
        $importer->shouldReceive('requireUserSettings')->once()->andReturn(false);
        $this->be($this->user());
        $this->call('get', route('import.settings', ['settings']));
        $this->assertResponseStatus(302);
        $this->assertRedirectedToRoute('import.complete', ['settings']);
    }

    /**
     * @covers \FireflyIII\Http\Controllers\ImportController::start
     */
    public function testStart()
    {
        /** @var ImportProcedureInterface $procedure */
        $procedure = $this->mock(ImportProcedureInterface::class);

        $procedure->shouldReceive('runImport');

        $this->be($this->user());
        $this->call('post', route('import.start', ['complete']));
        $this->assertResponseStatus(200);
    }

    /**
     * @covers \FireflyIII\Http\Controllers\ImportController::status
     * Implement testStatus().
     */
    public function testStatus()
    {
        // complete
        $this->be($this->user());
        $this->call('get', route('import.status', ['complete']));
        $this->assertResponseStatus(200);
    }

    /**
     * @covers \FireflyIII\Http\Controllers\ImportController::upload
     */
    public function testUpload()
    {
        $path = resource_path('stubs/csv.csv');
        $file = new UploadedFile($path, 'upload.csv', filesize($path), 'text/csv', null, true);
        $this->call('POST', route('import.upload'), [], [], ['import_file' => $file], ['Accept' => 'application/json']);
        $this->assertResponseStatus(302);
    }
}
