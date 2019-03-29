<?php

namespace DDDHelper\SpecGen\Generator;

use DDDHelper\SpecGen\NotificationHelper;
use PhpSpec\CodeGenerator\Generator\Generator;
use PhpSpec\CodeGenerator\TemplateRenderer;
use PhpSpec\Console\ConsoleIO;
use PhpSpec\Locator\Resource;
use PhpSpec\Util\Filesystem;

class DomainEntityGenerator implements Generator
{
    use GeneratorHelper, NotificationHelper;

    public function supports(Resource $resource, string $generation, array $data): bool
    {
        return 'entity_generator' === $generation;
    }

    /**
     * @param ConsoleIO $io
     * @param TemplateRenderer $templates
     * @param Filesystem $filesystem
     */
    public function __construct(ConsoleIO $io, TemplateRenderer $templates, Filesystem $filesystem = null)
    {
        $this->io = $io;
        $this->templates = $templates;
        $this->filesystem = $filesystem ?: new Filesystem();
    }
}
