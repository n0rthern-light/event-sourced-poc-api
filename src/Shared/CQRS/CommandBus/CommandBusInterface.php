<?php

namespace App\Shared\CQRS\CommandBus;

interface CommandBusInterface
{
    public function execute(CommandInterface $command): void;
}