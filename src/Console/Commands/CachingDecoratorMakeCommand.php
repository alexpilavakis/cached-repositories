<?php

namespace Ulex\CachedRepositories\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;

class CachingDecoratorMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository:cachingdecorator {name : The required model name of the cachingdecorator class}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a repository caching decorator';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Decorator';

    /**
     * The name of class being generated.
     *
     * @var string
     */
    private $decoratorClass;

    /**
     * The name of class being generated.
     *
     * @var string
     */
    private $model;

    const FOLDER = 'Repositories\Decorators';

    /**
     * Execute the console command.
     *
     * @throws FileNotFoundException
     */
    public function handle()
    {
        $this->setDecoratorClass();
        parent::handle();
    }

    /**
     * Set repository class name
     *
     * @return  CachingDecoratorMakeCommand
     */
    private function setDecoratorClass()
    {
        $name = (trim($this->argument('name')));

        $this->model = $name;

        $this->decoratorClass = $name . 'CachingDecorator';

        return $this;
    }

    /**
     * @return string
     */
    protected function getNameInput()
    {
        return trim($this->decoratorClass);
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceClass($stub, $name)
    {
        if(!$this->argument('name')){
            throw new InvalidArgumentException("Missing required argument model name");
        }

        $stub = parent::replaceClass($stub, $name);

        return str_replace('Dummy', $this->model, $stub);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/Cachingdecorator.stub';
    }

    /**
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\\' . self::FOLDER;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the decorator.'],
        ];
    }

}
