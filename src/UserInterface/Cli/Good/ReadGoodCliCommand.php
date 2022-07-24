<?php

namespace App\UserInterface\Cli\Good;

use App\Good\Query\GoodDto;
use App\Good\Query\GoodQuery;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Stopwatch\Stopwatch;

class ReadGoodCliCommand extends Command
{
    private GoodQuery $query;

    public function __construct(GoodQuery $goodQuery)
    {
        parent::__construct('good:read');

        $this->query = $goodQuery;
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $stopwatch = new Stopwatch();
        for(;;) {
            $output->write(\sprintf("\033\143"));

            $stopwatch->reset();
            $stopwatch->start('command');

            $dtos = $this->query->queryAll();

            $time = $stopwatch->stop('command');

            $output->writeln('UUID | Code | Name | Price | Price updated on');
            /** @var GoodDto $dto */
            foreach($dtos as $dto) {
                $output->writeln($dto);
            }

            $output->writeln((string)$time);

            \sleep(1);
        }


        return Command::SUCCESS;
    }
}