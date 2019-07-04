<?php


namespace App\Command;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CancelOldSubscriptionCommand extends Command
{
    protected static $defaultName = 'app:cancel-old-subscriptions';

    public function run(InputInterface $input, OutputInterface $output)
    {

    }
}
