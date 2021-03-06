<?php

namespace DDDHelper\SpecGen\Command;

use DDDHelper\SpecGen\CreationHelper;
use DDDHelper\SpecGen\NotificationHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateCommand extends Command
{
    use CreationHelper, NotificationHelper;

    protected function configure()
    {
        $this->setName('command');
        $this->setDescription('Creates a specification and implementation of an application command');

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
        for (; ;) {
            $propertyName = $this->ask(
                $input,
                $output,
                'Enter command data (Ex.: customerId, password, fullName, email): ',
                ''
            );
            if ($propertyName === '') {
                break;
            }

            $propertyName = addslashes($propertyName);
            $propertyType = $this->choose(
                $input,
                $output,
                'Choose data type of ' . $propertyName . ': ',
                [
                    'string',
                    'int',
                    'float',
                    'array',
                    'bool',
                    'class'
                ]
            );

            $arguments['fields'][$propertyName] = $propertyType;

            if ($propertyType === 'class') {
                while (true) {
                    $className = $this->ask($input, $output, 'Enter class or interface fully qualified name ' .
                        '(Ex.: MyVendor\\MyProject\\MyClass): ', '');

                    if ($className) {
                        $className = str_replace('/', '\\', $className);
                        $arguments['fields'][$propertyName] = $className;
                        break;
                    }
                }
            }
        }
        return $arguments;
    }
}
