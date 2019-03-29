<?php

namespace DDDHelper\SpecGen;

use DDDHelper\SpecGen\Generator\Cased;
use PhpSpec\Console\ConsoleIO;
use PhpSpec\Locator\Resource;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

trait NotificationHelper
{
    /**
     * @var ConsoleIO
     */
    protected $io;
    /**
     * @param Resource $resource
     * @param $class
     */
    protected function informExampleAdded(Resource $resource, Cased $class): void
    {
        $this->io->writeln(sprintf(
            "\nExample for <info>Class <value>%s()</value> has been created.</info>\n",
            $resource->getSrcClassname(),
            $class->asCamelCase()
        ), 2);
    }

    /**
     * @param InputInterface $input
     * @param $classname
     * @param $method
     * @return bool
     */
    protected function confirm(InputInterface $input, $classname)
    {
        $question = sprintf('Do you want to generate an example for %s?: ', $classname);
        $io = $this->getApplication()->getContainer()->get('console.io');
        return $io->askConfirmation($question, false);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param $question
     * @param array $choices
     * @return mixed
     */
    protected function choose(InputInterface $input, OutputInterface $output, $question, array $choices)
    {
        return str_replace(' ', '-', $this->getHelper('question')->ask(
            $input,
            $output,
            new ChoiceQuestion(
                $question,
                $choices
            )
        ));
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param $question
     * @param null $example
     * @return mixed
     */
    protected function ask(InputInterface $input, OutputInterface $output, $question, $example = null)
    {
        return $this->getHelper('question')->ask(
            $input,
            $output,
            new Question(
                $question,
                $example
            )
        );
    }
}
