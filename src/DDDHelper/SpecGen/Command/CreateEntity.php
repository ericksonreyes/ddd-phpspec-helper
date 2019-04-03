<?php

namespace DDDHelper\SpecGen\Command;

use DDDHelper\SpecGen\CreationHelper;
use DDDHelper\SpecGen\NotificationHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateEntity extends Command
{
    use CreationHelper, NotificationHelper;

    protected function configure()
    {
        $this->setName('entity');
        $this->setDescription('Creates a specification and implementation of a domain entity');

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
        for (;;) {
            $propertyName = $this->ask(
                $input,
                $output,
                'Enter additional entity data (Ex.: password, fullName, email): ',
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
                    'array',
                    'bool'
                ]
            );
            $arguments['fields'][$propertyName] = $propertyType;
        }
        return $arguments;
    }
}
