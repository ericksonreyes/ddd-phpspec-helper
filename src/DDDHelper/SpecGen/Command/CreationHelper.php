<?php

namespace DDDHelper\SpecGen;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

trait CreationHelper
{

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getApplication()->getContainer();
        $container->configure();
        $classname = $input->getArgument('class');

        $resource = $container->get('locator.resource_manager')->createResource($classname);

        if (file_exists($resource->getSpecFilename())) {
            $question = sprintf('Do you want to overwrite %s?: ', $classname);
            $io = $container->get('console.io');
            if (!$io->askConfirmation($question, false)) {
                return;
            }
        } else {
            if (!$this->confirm($input, $classname)) {
                return;
            }
        }

        $arguments = [
            'name' => $classname,
            'type' => $this->getName(),
        ];
        $arguments = $this->makeEventArguments($input, $output, $arguments);
        $container->get('code_generator')->generate($resource, $this->getName() . '_generator', $arguments);
    }
}
