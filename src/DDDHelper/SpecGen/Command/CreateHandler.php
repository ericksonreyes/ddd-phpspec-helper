<?php

namespace DDDHelper\SpecGen\Command;

use DDDHelper\SpecGen\CreationHelper;
use DDDHelper\SpecGen\NotificationHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateHandler extends Command
{
    use CreationHelper, NotificationHelper;

    protected function configure()
    {
        $this->setName('handler');
        $this->setDescription('Creates a specification and implementation of an application command handler');

        $this->setDefinition(array(
            new InputArgument('class', InputArgument::REQUIRED, 'Class method belongs to'),
        ));

        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param array $arguments
     * @return array
     */
    protected function makeEventArguments(InputInterface $input, OutputInterface $output, array $arguments): array
    {
        while (true) {
            $arguments['command'] = $this->ask(
                $input,
                $output,
                'Enter command to be handled (Ex.: SignUpUser, CreateOrder, RemoveItem): '
            );
            if ($arguments['command']) {
                break;
            }
        }

        for (; ;) {
            $propertyName = $this->ask(
                $input,
                $output,
                'Enter handler dependency (Ex.: repository, mailerService, fileSystem): ',
                ''
            );
            if ($propertyName === '') {
                break;
            }

            $propertyType = $this->choose(
                $input,
                $output,
                'Choose data type of ' . $propertyName . ': ',
                [
                    'string',
                    'int',
                    'float',
                    'array',
                    'bool'
                ]
            );
            $arguments['fields'][$propertyName] = $propertyType;
        }
        return $arguments;
    }
}
