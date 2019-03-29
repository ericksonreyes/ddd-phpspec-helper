<?php

namespace DDDHelper\SpecGen\Command;

use DDDHelper\SpecGen\CreationHelper;
use DDDHelper\SpecGen\NotificationHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateEvent extends Command
{

    use CreationHelper, NotificationHelper;

    protected function configure()
    {
        $this->setName('event');
        $this->setDescription('Creates a specification and implementation of a domain event');

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
            $arguments['event_name'] = $this->ask(
                $input,
                $output,
                'Enter event name (Ex.: UserSignedUp, UserPurchasedItem, UserLoggedOut): '
            );
            if ($arguments['event_name']) {
                break;
            }
        }

        while (true) {
            $arguments['context'] = $this->ask(
                $input,
                $output,
                'Enter context name (Ex.: Sales, Security, Inventory): '
            );
            if ($arguments['context']) {
                break;
            }
        }

        while (true) {
            $arguments['entity'] = $this->ask(
                $input,
                $output,
                'Enter entity name (Ex.: User, Product, Order): '
            );
            if ($arguments['entity']) {
                break;
            }
        }

        for (;;) {
            $propertyName = $this->ask(
                $input,
                $output,
                'Enter additional event data (Ex.: password, fullName, email): ',
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
            if (!isset($arguments['entity_id'])) {
                $arguments['entity_id'] = $propertyName;
            }
        }
        return $arguments;
    }
}
