<?php

namespace App\UserInterface\Cli\Good;

use App\Good\Command\UpdateGoodCommand;
use App\Good\Query\GoodDto;
use App\Good\Query\GoodQuery;
use App\Shared\CQRS\CommandBus\CommandBusInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Stopwatch\Stopwatch;

class FakeSeedUpdateGoodCliCommand extends Command
{
    private GoodQuery $query;
    private CommandBusInterface $commandBus;

    public function __construct(GoodQuery $query, CommandBusInterface $commandBus)
    {
        $this->query = $query;
        $this->commandBus = $commandBus;

        parent::__construct('good:fake-seed');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $stopwatch = new Stopwatch();

        for(;;) {
            $dtos = $this->query->queryAll();

            /** @var GoodDto $dto */
            foreach($dtos as $dto) {
                $code = $dto->code;

                if ($code === 'USD') {
                    continue;
                }

                $stopwatch->reset();
                $stopwatch->start('command');

                $price = (float)(\rand(100, 10000) / 100);

                $this->commandBus->execute(
                    new UpdateGoodCommand(
                        $code,
                        $price,
                    )
                );

                $time = $stopwatch->stop('command');

                $output->writeln($code . ' updated to ' . $price . ' USD -> ' . $time);
            }

            $output->write(\sprintf("\033\143"));
        }

        return Command::SUCCESS;
    }
}