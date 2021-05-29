<?php

namespace Ulex\CachedRepositories\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;

class InterfaceMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository:interface {name : The required model name of the repository interface}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a repository interface';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Interface';

    /**
     * The name of class being generated.
     *
     * @var string
     */
    private $interfaceClass;

    const FOLDER = 'Repositories\Interfaces';
    /**
     * @var string
     */
    private $model;

    /**
     * Execute the console command.
     *
     * @throws FileNotFoundException
     */
    public function handle()
    {
        $this->setInterfaceClass();
        parent::handle();
    }

    /**
     * Set interface class name
     * @return void
     */
    private function setInterfaceClass(): void
    {
        $name = (trim($this->argument('name')));

        $this->model = $name;

        $this->interfaceClass = $name . 'RepositoryInterface';
    }

    /**
     * @return string
     */
    protected function getNameInput()
    {
        return trim($this->interfaceClass);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/Interface.stub';
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
            ['name', InputArgument::REQUIRED, 'The name of the contract.'],
        ];
    }
}
