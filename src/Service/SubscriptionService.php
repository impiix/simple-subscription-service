<?php


namespace App\Service;


use App\Entity\Subscription;
use App\Repository\SubscriptionRepository;
use App\Security\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Psr\Log\LoggerInterface;

class SubscriptionService
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * SubscriptionService constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager, \Swift_Mailer $mailer, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;
    }

    public function activateAndSendMail(User $user): void
    {
        $subscriptionRepo = $this->entityManager->getRepository(Subscription::class);
        /**
         * @var SubscriptionRepository $subscriptionRepo
         */

        try {
            $subscription = $subscriptionRepo->findNewByUser($user->getId());
        } catch (NonUniqueResultException $e) {
            $this->logger->alert(sprintf('Problem with subscription for user: %s', $user->getId()));

            return;
        }
        $subscription->activate();
        $this->entityManager->persist($subscription);
        $this->entityManager->flush();

        $this->sendMail($user);
    }

    public function cancelOldSubscriptions(): void
    {
        $subscriptionRepo = $this->entityManager->getRepository(Subscription::class);
        /**
         * @var SubscriptionRepository $subscriptionRepo
         */

        $subscriptions = $subscriptionRepo->getNotPaidSubscriptions();
        foreach ($subscriptions as $subscription) {
            $subscription->deactivate();
            $this->entityManager->persist($subscription);
        }

        $this->entityManager->flush();
    }

    protected function sendMail(User $user): void
    {
        //$this->mailer->send();
    }
}
