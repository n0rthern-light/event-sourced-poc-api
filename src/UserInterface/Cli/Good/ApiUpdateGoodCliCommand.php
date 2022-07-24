<?php

namespace App\UserInterface\Cli\Good;

use App\Good\Command\UpdateGoodCommand;
use App\Good\Query\GoodDto;
use App\Good\Query\GoodQuery;
use App\Shared\CQRS\CommandBus\CommandBusInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Stopwatch\Stopwatch;

class ApiUpdateGoodCliCommand extends Command
{
    private GoodQuery $query;
    private CommandBusInterface $commandBus;

    public function __construct(GoodQuery $query, CommandBusInterface $commandBus)
    {
        $this->query = $query;
        $this->commandBus = $commandBus;

        parent::__construct('good:api-update');
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


                try {
                    $json = file_get_contents('https://www.alphavantage.co/query?function=CURRENCY_EXCHANGE_RATE&from_currency='. $code .'&to_currency=USD&apikey=8GTNSGDO00FIRSGU');
                    $data = json_decode($json, true);
                    $price = (float)\array_values(\array_values($data)[0])[4];
                } catch (\Throwable $ex) {
                    dump($ex->getMessage(), $data);
                }

                $stopwatch->reset();
                $stopwatch->start('command');

                $this->commandBus->execute(
                    new UpdateGoodCommand(
                        $code,
                        $price,
                    )
                );

                $time = $stopwatch->stop('command');

                $output->writeln($code . ' updated to ' . $price . ' USD -> ' . $time);

                \sleep(15);
            }

            $output->write(\sprintf("\033\143"));

            \sleep(1);
        }

        return Command::SUCCESS;
    }
}