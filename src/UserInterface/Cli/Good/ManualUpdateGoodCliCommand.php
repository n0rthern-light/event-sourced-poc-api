<?php

namespace App\UserInterface\Cli\Good;

use App\Good\Command\UpdateGoodCommand;
use App\Shared\CQRS\CommandBus\CommandBusInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Stopwatch\Stopwatch;

class ManualUpdateGoodCliCommand extends Command
{
    private CommandBusInterface $commandBus;

    public function __construct(CommandBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;

        parent::__construct('good:manual-update');

        $this->addArgument('goodCode', InputArgument::REQUIRED);
        $this->addArgument('goodPriceInUsd', InputArgument::REQUIRED);
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $stopwatch = new Stopwatch();
        $stopwatch->start('command');

        $this->commandBus->execute(
            new UpdateGoodCommand(
                $input->getArgument('goodCode'),
                $input->getArgument('goodPriceInUsd')
            )
        );

        $time = $stopwatch->stop('command');

        $output->writeln('Success! ' . $time);

        return Command::SUCCESS;
    }
}