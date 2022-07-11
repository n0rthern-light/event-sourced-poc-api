<?php

namespace App\Shared\CQRS\CommandBus;

use Psr\Container\ContainerInterface;

final class CommandBusImpl implements CommandBusInterface
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function execute(CommandInterface $command): void
    {
        if ($this->container->has($command::class)) {
            $handler = $this->container->get($command::class);
            $handler($command);

            return;
        }

        throw new CommandHandlerNotFoundException('CommandHandler for ' . $command::class . ' not found!');
    }
}