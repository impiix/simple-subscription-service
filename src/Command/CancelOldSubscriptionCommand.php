<?php


namespace App\Command;


use App\Service\SubscriptionService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CancelOldSubscriptionCommand extends Command
{
    protected static $defaultName = 'app:cancel-old-subscriptions';

    /**
     * @var SubscriptionService
     */
    protected $subscriptionService;

    /**
     * CancelOldSubscriptionCommand constructor.
     * @param SubscriptionService $subscriptionService
     */
    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;

        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output): void
    {
        $output->writeln('Cancelling old subscriptions...');
        $this->subscriptionService->cancelOldSubscriptions();
        $output->writeln('Done.');
    }
}
