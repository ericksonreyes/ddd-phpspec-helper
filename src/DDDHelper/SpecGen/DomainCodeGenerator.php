<?php

namespace DDDHelper\SpecGen;

use DDDHelper\SpecGen\Command\CreateCommand;
use DDDHelper\SpecGen\Command\CreateEntity;
use DDDHelper\SpecGen\Command\CreateEvent;
use DDDHelper\SpecGen\Command\CreateHandler;
use DDDHelper\SpecGen\Generator\DomainCommandGenerator;
use DDDHelper\SpecGen\Generator\DomainEntityGenerator;
use DDDHelper\SpecGen\Generator\DomainEventGenerator;
use DDDHelper\SpecGen\Generator\DomainHandlerGenerator;
use PhpSpec\Extension;
use PhpSpec\ServiceContainer;

class DomainCodeGenerator implements Extension
{
    /**
     * @param ServiceContainer $container
     * @param array $params
     */
    public function load(ServiceContainer $container, array $params): void
    {
        $container->define('code_generator.generators.event_generator', function (ServiceContainer $c) {
            return new DomainEventGenerator($c->get('console.io'), $c->get('code_generator.templates'));
        }, ['code_generator.generators']);

        $container->define('console.commands.create.event', function () {
            return new CreateEvent();
        }, ['console.commands']);


        $container->define('code_generator.generators.entity_generator', function (ServiceContainer $c) {
            return new DomainEntityGenerator($c->get('console.io'), $c->get('code_generator.templates'));
        }, ['code_generator.generators']);

        $container->define('console.commands.create.entity', function () {
            return new CreateEntity();
        }, ['console.commands']);


        $container->define('code_generator.generators.command_generator', function (ServiceContainer $c) {
            return new DomainCommandGenerator($c->get('console.io'), $c->get('code_generator.templates'));
        }, ['code_generator.generators']);

        $container->define('console.commands.create.command', function () {
            return new CreateCommand();
        }, ['console.commands']);


        $container->define('code_generator.generators.handler_generator', function (ServiceContainer $c) {
            return new DomainHandlerGenerator($c->get('console.io'), $c->get('code_generator.templates'));
        }, ['code_generator.generators']);

        $container->define('console.commands.create.handler', function () {
            return new CreateHandler();
        }, ['console.commands']);
    }
}
