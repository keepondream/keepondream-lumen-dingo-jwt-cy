<?php
/**
 * Description:
 * Author: WangSx
 * DateTime: 2019-07-08 11:07
 */

namespace App\Console\Commands\CodeGenCommands;


use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Composer;

class CreateRequestServiceController extends Command
{
    /**
     * 控制台命令 signature 的名称。
     *
     * @var string
     */
    protected $signature = 'controller:create {modelName}';

    /**
     * 控制台命令说明。
     *
     * @var string
     */
    protected $description = 'create Request Service Controller';

    /**
     * @var Filesystem
     */
    protected $files;

    /**
     * @var Composer
     */
    protected $composer;

    /**
     * @var string
     */
    protected $date;

    /**
     * @var string
     */
    protected $time;

    /**
     * @var string
     */
    protected $modelName;

    /**
     * @var string
     */
    protected $folderName;

    protected const BASE_PATH = 'App' . DIRECTORY_SEPARATOR;

    protected const SERVICE_PATH = self::BASE_PATH . 'Services';

    protected const SERVICE_CONSTRUCT_SERVICES_PATH = self::SERVICE_PATH . DIRECTORY_SEPARATOR . 'ConstructInterfaces';

    protected const REQUEST_PATH = self::BASE_PATH . 'Http' . DIRECTORY_SEPARATOR . 'Requests';

    protected const CONTROLLER_PATH = self::BASE_PATH . 'Http' . DIRECTORY_SEPARATOR . 'Controllers';

    protected $create_dirs = [];

    protected const REQUEST_OPTION = [
        'GetRequest', 'CreateRequest', 'UpdateRequest', 'DeleteRequest', 'ListRequest'
    ];

    protected const CREATE_FILES = [
        'request' => self::REQUEST_OPTION,
        'controller' => 'Controller',
        'service' => 'Service',
        'serviceInterface' => 'ServiceInterface'
    ];

    protected const STUB_FILE_PATH = self::BASE_PATH . 'Console' . DIRECTORY_SEPARATOR . 'Commands' . DIRECTORY_SEPARATOR . 'CodeGenCommands' . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . 'ServiceFile';


    public function __construct(Filesystem $filesystem, Composer $composer)
    {
        parent::__construct();
        $this->files = $filesystem;
        $this->composer = $composer;
        $this->date = date('Y-m-d');
        $this->time = date('H:i');
    }

    /**
     * 执行控制台命令。
     *
     * @return mixed
     */
    public function handle()
    {
        $fullModelName = $this->argument('modelName');
        $name_arr = explode('/', $fullModelName);
        $modelName = array_pop($name_arr);
        if (!empty($modelName)) {
            $modelName = str_replace('Controller', '', $modelName);
            $modelName = str_replace('controller', '', $modelName);
        }

        $this->folderName = $this->modelName = ucfirst($modelName);

        if (!empty($name_arr)) {
            $controller_dir = DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $name_arr);
        } else {
            $controller_dir = '';
        }

        $this->create_dirs = [
            'request' => self::REQUEST_PATH,
            'controller' => self::CONTROLLER_PATH . $controller_dir,
            'service' => self::SERVICE_PATH,
            'serviceInterface' => self::SERVICE_CONSTRUCT_SERVICES_PATH
        ];


        # 创建所有文件目录
        $this->createAllDirectory();

        # 生成所有文件
        $this->createAllFile();

        # 重载autoload
        $this->composer->dumpAutoloads();

    }

    protected function createAllFile()
    {
        # 渲染模板文件,替换模板中的变量
        $templates = $this->templateStub();

        foreach ($templates as $k => $template) {
            if (is_array($template)) {
                foreach ($template as $index => $item) {
                    $file_path = $this->getPath($k, $index);
                    if (!$this->files->exists($file_path)) {
                        $this->files->put($this->getPath($k, $index), $item);
                        $filename = substr(strrchr($file_path, "/"), 1);
                        $this->info('create : ' . $filename . '  success');
                    }
                }
            } else {
                $file_path = $this->getPath($k);
                if (!$this->files->exists($file_path)) {
                    $this->files->put($this->getPath($k), $template);
                    $filename = substr(strrchr($file_path, "/"), 1);
                    $this->info('create : ' . $filename . '  success');
                }
            }
        }

        return true;
    }

    protected function getPath($k, $index = 0)
    {
        $createFiles = self::CREATE_FILES;
        if (is_array($createFiles[$k])) {
            return $this->create_dirs[$k] . DIRECTORY_SEPARATOR . $this->folderName . DIRECTORY_SEPARATOR . $this->modelName . $createFiles[$k][$index] . '.php';
        } else {
            return $this->create_dirs[$k] . DIRECTORY_SEPARATOR . $this->folderName . DIRECTORY_SEPARATOR . $this->modelName . $createFiles[$k] . '.php';
        }
    }

    protected function createAllDirectory()
    {
        foreach ($this->create_dirs as $dir) {
            $filePath = $dir . DIRECTORY_SEPARATOR . $this->folderName;
            if (!$this->files->isDirectory($filePath)) {
                $this->files->makeDirectory($filePath, 0755, true);
            }
        }

        return true;
    }

    protected function templateStub()
    {
        # 获取模板文件
        $stubs = $this->getStub();

        # 获取需要替换的模板文件中的变量
        $renderStubs = [];

        foreach ($stubs as $k => $stub) {
            $createFiles = self::CREATE_FILES;
            if (is_array($createFiles[$k])) {
                $files = $createFiles[$k];
                foreach ($files as $file) {
                    $templateData = $this->getTemplateData($k);
                    $templateData['fileName'] = $this->modelName . $file;
                    $templateData['className'] = $this->modelName . $file;
                    // 进行模板渲染
                    $renderStubs[$k][] = $this->getRenderStub($templateData, $stub);
                }
            } else {
                $templateData = $this->getTemplateData($k);
                // 进行模板渲染
                $renderStubs[$k] = $this->getRenderStub($templateData, $stub);
            }
        }

        return $renderStubs;
    }

    protected function getRenderStub($templateData, $stub)
    {
        foreach ($templateData as $search => $replace) {
            $stub = str_replace('$' . $search, $replace, $stub);
        }

        return $stub;
    }

    protected function getTemplateData($k)
    {
        $createFiles = self::CREATE_FILES;

        if (is_array($createFiles[$k])) {
            $fileName = '';
        } else {
            $fileName = $this->modelName . $createFiles[$k];
        }

        $dirs = $this->create_dirs;
        $dir = $dirs[$k] . DIRECTORY_SEPARATOR . $this->folderName;
        $nameSpace = str_replace('/', '\\', $dir);
        $templateVar = [
            'date' => $this->date,
            'time' => $this->time,
            'folderName' => $this->folderName,
            'fileName' => $fileName,
            'className' => $fileName,
            'nameSpace' => $nameSpace
        ];

        return $templateVar;
    }

    protected function getStub()
    {
        $stubs = [];
        $files = self::CREATE_FILES;
        foreach ($files as $k => $file) {
            $file_path = self::STUB_FILE_PATH . DIRECTORY_SEPARATOR . $k . '.stub';
            if ($this->files->isFile($file_path)) {
                $stubs[$k] = $this->files->get($file_path);
            }
        }
        return $stubs;
    }

    /**
     * Description:
     * Author: WangSx
     * DateTime: 2019-07-08 11:41
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->{$name} = $value;
    }
}