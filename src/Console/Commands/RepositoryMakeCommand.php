<?php

namespace Ulex\CachedRepositories\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;

class RepositoryMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {name : The required model name of the repository class} {--all : Include interface and caching decorator}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a repository with decorator and interface';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Repository';

    /**
     * The name of class being generated.
     *
     * @var string
     */
    private $repositoryClass;

    /**
     * The name of class being generated.
     *
     * @var string
     */
    private $model;

    const FOLDER = 'Repositories\Eloquent';

    /**
     * Execute the console command.
     *
     * @throws FileNotFoundException
     */
    public function handle()
    {
        $this->setRepositoryClass();
        parent::handle();
        if ($this->option('all')) {
            $this->call('make:repository:interface', [
                'name' => $this->argument('name')
            ]);
            $this->call('make:repository:cachingdecorator', [
                'name' => $this->argument('name')
            ]);
        }
        $this->line("<info>Add Model in `models` array in config/cached-repositories.php</info>");
    }

    /**
     * Set repository class name
     *
     * @return  RepositoryMakeCommand
     */
    private function setRepositoryClass()
    {
        $name = (trim($this->argument('name')));

        $this->model = $name;

        $this->repositoryClass = $name . 'Repository';

        return $this;
    }

    /**
     * @return string
     */
    protected function getNameInput()
    {
        return trim($this->repositoryClass);
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

        return str_replace('DummyModel', $this->model, $stub);
    }

    /**
     *
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/Repository.stub';
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
            ['name', InputArgument::REQUIRED, 'The name of the model class.'],
        ];
    }

}
