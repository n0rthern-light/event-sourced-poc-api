<?php

namespace App\UserInterface\Cli\Good;

use App\Good\Command\UpdateGoodPriceCommand;
use App\Shared\CQRS\CommandBus\CommandBusInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateGoodsCommand extends Command
{
    private CommandBusInterface $commandBus;

    public function __construct(CommandBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;

        parent::__construct('good:update-all');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->commandBus->execute(
            new UpdateGoodPriceCommand(
                'USD',
                125
            )
        );

        return Command::SUCCESS;
    }
}