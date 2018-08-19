<?php
namespace Prettus\Repository\Generators;

/**
 * Class ControllerGenerator
 * @package Prettus\Repository\Generators
 * @author Anderson Andrade <contato@andersonandra.de>
 */
class ResourceTraitGenerator extends Generator
{

    /**
     * Get stub name.
     *
     * @var string
     */
    protected $stub = 'controller/trait/resource';

    /**
     * Get root namespace.
     *
     * @return string
     */
    public function getRootNamespace()
    {
        return str_replace('/', '\\', parent::getRootNamespace() . parent::getConfigGeneratorClassPath($this->getPathConfigNode(), true));
    }

    /**
     * Get generator path config node.
     *
     * @return string
     */
    public function getPathConfigNode()
    {
        return 'resources_trait';
    }

    /**
     * Get destination path for generated file.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->getBasePath() .'/'. parent::getConfigGeneratorClassPath($this->getPathConfigNode(), true) . '/' . $this->getTraitName() . 'Resource.php';
    }

    /**
     * Get base path of destination file.
     *
     * @return string
     */
    public function getBasePath()
    {
        return config('repository.generator.basePath', app()->path());
    }

    /**
     * Gets plural name based on model
     *
     * @return string
     */
    public function getPluralName()
    {

        return str_plural(lcfirst(ucwords($this->getClass())));
    }

    /**
     * Get array replacements.
     *
     * @return array
     */
    public function getReplacements()
    {

        return array_merge(parent::getReplacements(), [
            'trait' => $this->getTraitName(),
            'plural'     => $this->getPluralName(),
            'singular'   => $this->getSingularName(),
            'repository' => $this->getRepository(),
            'appname'    => $this->getAppNamespace(),
        ]);
    }

    public function getTraitName()
    {
        return ucfirst($this->getPluralName());
    }

    /**
     * Gets singular name based on model
     *
     * @return string
     */
    public function getSingularName()
    {
        return str_singular(lcfirst(ucwords($this->getClass())));
    }

    /**
     * Gets repository full class name
     *
     * @return string
     */
    public function getRepository()
    {
        $repositoryGenerator = new RepositoryInterfaceGenerator([
            'name' => $this->name,
        ]);

        $repository = $repositoryGenerator->getRootNamespace() . '\\' . $repositoryGenerator->getName();

        return 'use ' . str_replace([
            "\\",
            '/'
        ], '\\', $repository) . 'Repository;';
    }
}
