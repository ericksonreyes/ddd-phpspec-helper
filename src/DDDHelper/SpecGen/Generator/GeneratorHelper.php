<?php

namespace DDDHelper\SpecGen\Generator;

use PhpSpec\CodeGenerator\TemplateRenderer;
use PhpSpec\Console\ConsoleIO;
use PhpSpec\Locator\Resource;
use PhpSpec\Util\Filesystem;

trait GeneratorHelper
{
    /**
     * @var ConsoleIO
     */
    protected $io;

    /**
     * @var TemplateRenderer
     */
    protected $templates;
    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @param Resource $resource
     * @param array $data
     */
    public function generate(Resource $resource, array $data = []): void
    {
        $name = $data['name'] ?? '';
        $type = $data['type'] ?? '';
        $context = $data['context'] ?? '';
        $entity = $data['entity'] ?? '';
        $eventName = $data['event_name'] ?? '';
        $command = $data['command'] ?? '';
        $class = new Cased($name);
        $templateVariables =
            [
                '%event_name%' => Cased::make($eventName)->asCamelCase(),
                '%context%' => Cased::make($context)->asCamelCase(),
                '%entity%' => Cased::make($entity)->asCamelCase(),

                '%spec_class%' => $resource->getSpecName(),
                '%spec_namespace%' => $resource->getSpecNamespace(),
                '%spec_constructor_fields%' => $this->generateSpecConstructorDependencies($data),
                '%spec_constructor_fields_semicolon_delimited%' =>
                    $this->generateSpecConstructorDependenciesSemiColonDelimited($data),
                '%spec_fields%' => $this->generateSpecFields($data),
                '%spec_getter_tests%' => $this->generateSpecGetterTest($data),
                '%spec_data_array%' => $this->generateSpecData($data),
                '%spec_data_test%' => $this->generateSpecDataTest($data),

                '%src_namespace%' => $resource->getSrcNamespace(),
                '%src_full_qualified_class_name%' => $resource->getSrcClassname(),
                '%src_class%' => $resource->getName(),
                '%src_constructor_fields%' => $this->generateSrcConstructorDependencies($data),
                '%src_comma_prefixed_constructor_fields%' => $this->generateCommaPrefixedSrcConstructorDependencies($data),
                '%src_constructor_initialization%' => $this->generateSrcConstructorInitialization($data),
                '%src_dto_constructor_initialization%' => $this->generateSrcDtoConstructorInitialization($data),
                '%src_constructor_docs%' => $this->generateSrcConstructorDocumentation($data),
                '%src_getters%' => $this->generateSrcGetters($data),
                '%src_nullable_getters%' => $this->generateSrcNullableGetters($data),
                '%src_data_to_array%' => $this->generateSrcDataToArray($data),
                '%src_array_to_data%' => $this->generateSrcArrayToData($data),
                '%src_fields%' => $this->generateSrcFields($data),

                '%command%' => ucfirst(Cased::make($command)->asCamelCase())
            ];

        $specBasePath = rtrim($resource->getSpecFilename(), $resource->getSpecName() . '.php');

        if (!$this->filesystem->pathExists($specBasePath)) {
            $this->filesystem->makeDirectory($specBasePath);
        }
        $specContent = $this->templates->renderString($this->getTemplate($type . '_spec'), $templateVariables);
        $this->filesystem->putFileContents($resource->getSpecFilename(), $specContent);

        $srcBasePath = rtrim($resource->getSrcFilename(), $resource->getSrcClassname() . '.php');
        if (!$this->filesystem->pathExists($srcBasePath)) {
            $this->filesystem->makeDirectory($srcBasePath);
        }

        $srcContent = $this->templates->renderString($this->getTemplate($type . '_src'), $templateVariables);
        $this->filesystem->putFileContents($resource->getSrcFilename(), $srcContent);


        $this->informExampleAdded($resource, $class);
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return 0;
    }

    /**
     * @param array $data
     * @return string
     */
    protected function generateSpecData(array $data): string
    {
        if (!isset($data['fields'])) {
            return '';
        }

        $eventData = [];
        foreach ((array)$data['fields'] as $fieldName => $fieldType) {
            $eventData[] = "\n\t\t\t\t"
                . '\'' . lcfirst(Cased::make($fieldName)->asCamelCase())
                . '\' => $expected' . Cased::make($fieldName)->asCamelCase()
                . ' = $this->seeder->'
                . $this->getSeederType($fieldType);
        }
        return implode(', ', $eventData);
    }

    /**
     * @param array $data
     * @return string
     */
    protected function generateSrcDataToArray(array $data): string
    {
        if (!isset($data['fields'])) {
            return '';
        }

        $eventData = [];
        foreach ((array)$data['fields'] as $fieldName => $fieldType) {
            $variableName = lcfirst(Cased::make($fieldName)->asCamelCase());
            $eventData[] = "\n\t\t\t\t"
                . '\'' . $variableName . '\' => $this->' . $variableName . '()';
        }
        return implode(', ', $eventData);
    }

    /**
     * @param array $data
     * @return string
     */
    protected function generateSrcArrayToData(array $data): string
    {
        if (!isset($data['fields'])) {
            return '';
        }

        $eventData = [];
        foreach ((array)$data['fields'] as $fieldName => $fieldType) {
            $variableName = lcfirst(Cased::make($fieldName)->asCamelCase());
            $eventData[] = "\n\t\t"
                . '$event->' . $variableName . ' = $array[\'data\'][\'' . $variableName . '\'];';
        }
        return implode('', $eventData);
    }

    /**
     * @param array $data
     * @return string
     */
    protected function generateSpecDataTest(array $data): string
    {
        if (!isset($data['fields'])) {
            return '';
        }

        $eventDataTest = [];
        foreach ((array)$data['fields'] as $fieldName => $fieldType) {
            $eventDataTest[] = '$this::fromArray($array)->'
                . lcfirst(Cased::make($fieldName)->asCamelCase())
                . '()->shouldReturn($expected' . Cased::make($fieldName)->asCamelCase() . ');';
        }
        return implode("\n\t\t", $eventDataTest);
    }

    /**
     * @param array $data
     * @return string
     */
    protected function generateSpecGetterTest(array $data): string
    {
        if (!isset($data['fields'])) {
            return '';
        }

        $eventDataTest = [];
        foreach ((array)$data['fields'] as $fieldName => $fieldType) {
            $eventDataTest[] .= '
    public function it_has_' . lcfirst(Cased::make($fieldName)->asCamelCase()) . '()
    {
        $this->' . lcfirst(Cased::make($fieldName)->asCamelCase())
                . '()->shouldReturn($this->expected'
                . Cased::make($fieldName)->asCamelCase() . ');
    }
';
        }
        return implode("\n", $eventDataTest);
    }

    /**
     * @param array $data
     * @return string
     */
    protected function generateSrcGetters(array $data): string
    {
        if (!isset($data['fields'])) {
            return '';
        }

        $eventDataTest = [];
        foreach ((array)$data['fields'] as $fieldName => $fieldType) {
            $variableName = lcfirst(Cased::make($fieldName)->asCamelCase());
            $type = $this->getFieldType($fieldType);
            $eventDataTest[] .= '
    /**
    * @return ' . $type . '
    */
    public function ' . $variableName . '(): ' . $type . '
    {
        return $this->' . $variableName . ';
    }
';
        }
        return implode("\n", $eventDataTest);
    }

    /**
     * @param array $data
     * @return string
     */
    protected function generateSrcNullableGetters(array $data): string
    {
        if (!isset($data['fields'])) {
            return '';
        }

        $eventDataTest = [];
        foreach ((array)$data['fields'] as $fieldName => $fieldType) {
            $variableName = lcfirst(Cased::make($fieldName)->asCamelCase());
            $type = $this->getFieldType($fieldType);
            $eventDataTest[] .= '
    /**
    * @return ' . $type . '
    */
    public function ' . $variableName . '(): ?' . $type . '
    {
        return $this->' . $variableName . ';
    }
';
        }
        return implode("\n", $eventDataTest);
    }

    /**
     * @param array $data
     * @return array|string
     */
    protected function generateSpecFields(array $data): string
    {
        if (!isset($data['fields'])) {
            return '';
        }

        $eventFields = [];
        foreach ((array)$data['fields'] as $fieldName => $fieldType) {
            $eventFields[] = '/**';
            $eventFields[] = '* @var ' . $this->getFieldType($fieldType);
            $eventFields[] = '*/';
            $eventFields[] = 'protected $expected' . Cased::make($fieldName)->asCamelCase() . ';';
            $eventFields[] = '';
        }
        return implode("\n\t", $eventFields);
    }

    /**
     * @param array $data
     * @return array|string
     */
    protected function generateSrcFields(array $data): string
    {
        if (!isset($data['fields'])) {
            return '';
        }

        $eventFields = [];
        foreach ((array)$data['fields'] as $fieldName => $fieldType) {
            $eventFields[] = '/**';
            $eventFields[] = '/* @var ' . $this->getFieldType($fieldType);
            $eventFields[] = '*/';
            $eventFields[] = 'protected $' . lcfirst(Cased::make($fieldName)->asCamelCase()) . ';';
            $eventFields[] = '';
        }
        return implode("\n\t", $eventFields);
    }

    /**
     * @param array $data
     * @return array|string
     */
    protected function generateSpecConstructorDependencies(array $data): string
    {
        if (!isset($data['fields'])) {
            return '';
        }

        $constructorFields = [];
        foreach ((array)$data['fields'] as $fieldName => $fieldType) {
            $constructorFields[] = "\n\t\t\t"
                . '$this->expected'
                . Cased::make($fieldName)->asCamelCase()
                . ' = $this->seeder->'
                . $this->getSeederType($fieldType);
        }
        return implode(', ', $constructorFields);
    }

    /**
     * @param array $data
     * @return array|string
     */
    protected function generateSpecConstructorDependenciesSemiColonDelimited(array $data): string
    {
        if (!isset($data['fields'])) {
            return '';
        }

        $constructorFields = [];
        foreach ((array)$data['fields'] as $fieldName => $fieldType) {
            $constructorFields[] = "\n\t\t\t"
                . '$this->expected'
                . Cased::make($fieldName)->asCamelCase()
                . ' = $this->seeder->'
                . $this->getSeederType($fieldType) . ';';
        }
        return implode(' ', $constructorFields);
    }

    /**
     * @param array $data
     * @return array|string
     */
    protected function generateCommaPrefixedSrcConstructorDependencies(array $data): string
    {
        if (!isset($data['fields'])) {
            return '';
        }

        $constructorFields = [];
        foreach ((array)$data['fields'] as $fieldName => $fieldType) {
            $variableName = lcfirst(Cased::make($fieldName)->asCamelCase());
            $constructorFields[] = "\n\t\t"
                . $this->getFieldType($fieldType) .
                ' $' . $variableName;
        }

        if (count($constructorFields) > 0) {
            return ', ' . implode(', ', $constructorFields);
        }
        return '';
    }

    /**
     * @param array $data
     * @return array|string
     */
    protected function generateSrcConstructorDependencies(array $data): string
    {
        if (!isset($data['fields'])) {
            return '';
        }

        $constructorFields = [];
        foreach ((array)$data['fields'] as $fieldName => $fieldType) {
            $variableName = lcfirst(Cased::make($fieldName)->asCamelCase());
            $constructorFields[] = "\n\t\t"
                . $this->getFieldType($fieldType) .
                ' $' . $variableName;
        }

        return implode(', ', $constructorFields);;
    }

    /**
     * @param array $data
     * @return array|string
     */
    protected function generateSrcConstructorInitialization(array $data): string
    {
        if (!isset($data['fields'])) {
            return '';
        }

        $constructorFields = [];
        foreach ((array)$data['fields'] as $fieldName => $fieldType) {
            $variableName = lcfirst(Cased::make($fieldName)->asCamelCase());
            $constructorFields[] = "\n\t\t" . '$event->' . $variableName . ' = $' . $variableName . ';';
        }
        return implode('', $constructorFields);
    }

    /**
     * @param array $data
     * @return array|string
     */
    protected function generateSrcDtoConstructorInitialization(array $data): string
    {
        if (!isset($data['fields'])) {
            return '';
        }

        $constructorFields = [];
        foreach ((array)$data['fields'] as $fieldName => $fieldType) {
            $variableName = lcfirst(Cased::make($fieldName)->asCamelCase());
            $constructorFields[] = "\n\t\t" . '$this->' . $variableName . ' = $' . $variableName . ';';
        }
        return implode('', $constructorFields);
    }

    /**
     * @param array $data
     * @return array|string
     */
    protected function generateSrcConstructorDocumentation(array $data): string
    {
        if (!isset($data['fields'])) {
            return '';
        }

        $constructorFields = [];
        foreach ((array)$data['fields'] as $fieldName => $fieldType) {
            $variableName = lcfirst(Cased::make($fieldName)->asCamelCase());
            $constructorFields[] = "\n\t" . ' * @param ' . $this->getFieldType($fieldType) . ' $' . $variableName;
        }
        return implode('', $constructorFields);
    }

    /**
     * @param string $type
     * @return string
     */
    protected function getFieldType(string $type): string
    {
        switch ($type) {
            case 'int':
                return 'int';
            case 'float':
                return 'float';
            case 'array':
                return 'array';
            case 'bool':
                return 'bool';
        }
        return 'string';
    }

    /**
     * @param string $type
     * @return string
     */
    protected function getSeederType(string $type)
    {
        switch ($type) {
            case 'int':
                return 'numberBetween(1, 100000)';
            case 'float':
                return 'randomFloat(2, 1, 100000)';
            case 'array':
                return 'paragraphs';
            case 'bool':
                return 'boolean';
        }
        return 'word';
    }

    /**
     * @param $type
     * @return string
     */
    protected function getTemplate($type)
    {
        return file_get_contents(__DIR__ . '/templates/' . $type . '.template');
    }
}
