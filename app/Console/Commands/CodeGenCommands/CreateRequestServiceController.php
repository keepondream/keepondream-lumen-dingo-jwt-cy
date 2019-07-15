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
    protected $signature = 'controller:create {modelName} {--e}';

    /**
     * 控制台命令说明。
     *
     * @var string
     */
    protected $description = 'create Request Service Controller modelName {--e start exception template}';

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

    protected const EXCEPTIONS_PATH = self::BASE_PATH . 'Exceptions';

    protected const BO_EXCEPTIONS_PATH = self::EXCEPTIONS_PATH . DIRECTORY_SEPARATOR . 'BOExceptions';

    protected const EXCEPTION_CODE_JSON_PATH = self::EXCEPTIONS_PATH . DIRECTORY_SEPARATOR . 'code_min_max.json';

    protected $create_dirs = [];

    protected const REQUEST_OPTION = [
        'GetRequest', 'CreateRequest', 'UpdateRequest', 'DeleteRequest', 'ListRequest'
    ];

    protected const CREATE_FILES = [
        'request' => self::REQUEST_OPTION,
        'serviceInterface' => 'ServiceInterface',
        'service' => 'Service',
        'controller' => 'Controller',
        'BOException' => 'BOException'
    ];

    protected const STUB_FILE_PATH = self::BASE_PATH . 'Console' . DIRECTORY_SEPARATOR . 'Commands' . DIRECTORY_SEPARATOR . 'CodeGenCommands' . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . 'ServiceFile';


    protected $fullServiceNameSpace;

    protected $serviceName;

    protected $lServiceName;

    protected $exception;

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
        $exception = $this->option('e');

        if (!empty($exception)) {
            $this->exception = true;
        } else {
            $this->exception = false;
        }

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
            'serviceInterface' => self::SERVICE_CONSTRUCT_SERVICES_PATH,
            'service' => self::SERVICE_PATH,
            'controller' => self::CONTROLLER_PATH . $controller_dir,
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
            if ($k == 'BOException') {
                return self::BO_EXCEPTIONS_PATH . DIRECTORY_SEPARATOR . $this->modelName . $createFiles[$k] . '.php';
            } else {
                return $this->create_dirs[$k] . DIRECTORY_SEPARATOR . $this->folderName . DIRECTORY_SEPARATOR . $this->modelName . $createFiles[$k] . '.php';
            }
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
                    $templateData['folderName'] = $this->folderName;
                    $templateData['fileName'] = $this->modelName . $file;
                    $templateData['className'] = $this->modelName . $file;
                    // 进行模板渲染
                    $renderStubs[$k][] = $this->getRenderStub($templateData, $stub);
                }
            } else {
                # 过滤是否开启exception模板
                if (!$this->exception) {
                    # 没有开启exception模板,则进行过滤
                    if ($k != 'BOException') {
                        $templateData = $this->getTemplateData($k);
                        // 进行模板渲染
                        $renderStubs[$k] = $this->getRenderStub($templateData, $stub);
                    }
                } else {
                    $templateData = $this->getTemplateData($k);
                    // 进行模板渲染
                    $renderStubs[$k] = $this->getRenderStub($templateData, $stub);
                }
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

        # 获取
        if ($k == 'BOException') {
            $dir = self::BO_EXCEPTIONS_PATH;
            $code_min_max_json = $this->files->get(self::EXCEPTION_CODE_JSON_PATH);
            $data = json_decode($code_min_max_json, true);
            $newData = [];
            foreach ($data as $v) {
                if ($v['name'] == $fileName) {
                    $newData['name'] = $v['name'];
                    $newData['min'] = $v['min'];
                    $newData['max'] = $v['max'];
                }
            }
            if (empty($newData)) {
                $referData = end($data);
                $newData = [
                    'name' => $fileName,
                    'min' => $referData['min'] + 1000,
                    'max' => $referData['max'] + 1000
                ];
                array_push($data, $newData);
                $this->files->put(self::EXCEPTION_CODE_JSON_PATH, json_encode($data, JSON_UNESCAPED_UNICODE));
                $this->info('code_min_max.json init success');
            }

            $min_code = $newData['min'];
            $max_code = $newData['max'];
        } else {
            $dirs = $this->create_dirs;
            $dir = $dirs[$k] . DIRECTORY_SEPARATOR . $this->folderName;
            $min_code = 0;
            $max_code = 0;
        }
        $nameSpace = str_replace('/', '\\', $dir);
        $templateVar = [
            'date' => $this->date,
            'time' => $this->time,
            'folderName' => $this->folderName,
            'fileName' => $fileName,
            'className' => $fileName,
            'nameSpace' => $nameSpace,
            'lClassName' => lcfirst($fileName),
            'serviceName' => $this->serviceName,
            'lServiceName' => $this->lServiceName,
            'fullServiceNameSpace' => $this->fullServiceNameSpace,
            'min_code' => $min_code,
            'max_code' => $max_code
        ];

        # 服务层则进行service注入
        if ($k == 'service') {
            $serviceFileName = self::SERVICE_PATH . DIRECTORY_SEPARATOR . 'ServiceManager.php';
            $serviceFileContent = $this->files->get($serviceFileName);

            $outServiceFile = preg_replace('/;(\s)*?\/\*\*/', ';
use ' . $nameSpace . '\\' . $fileName . ';

/**', $serviceFileContent);

            $outServiceFile = preg_replace('/\)(\s)*?\*\//', ')
 * @method ' . $fileName . ' ' . lcfirst($fileName) . '(string $fullClassName)
 */', $outServiceFile);
            $this->files->put($serviceFileName, $outServiceFile);
            $this->serviceName = $fileName;
            $this->lServiceName = lcfirst($fileName);
            $this->fullServiceNameSpace = $nameSpace . '\\' . $fileName;

            $this->info('ServiceManager.php init success');
        }

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