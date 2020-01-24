<?php
namespace App\SymfonyPayments;

use App\Entity\Dispute;
use App\SymfonyPayments\Logger\EnvAwareLogger;
use App\SymfonyPayments\Model\ShoppyModel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class ShoppyClient {
    private $entityManager;
    private $logger;

    public function __construct(EntityManagerInterface $entityManager, EnvAwareLogger $envAwareLogger) {
        $this->entityManager = $entityManager;
        $this->logger = $envAwareLogger->getLogger();
    }

    public function verifyHmac($payload, $secret, $signature) {
        $hmac_enabled = filter_var($_ENV['HMAC_VERIFICATION_ENABLED'], FILTER_VALIDATE_BOOLEAN);
        if(!$hmac_enabled) {
            return true;
        }

        $calculated_hmac = hash_hmac('sha512', $payload, $secret);
        if(hash_equals($calculated_hmac, $signature)) {
            return true;
        }

        $this->logger->log("Webhook Verification Failed!", $_ENV["EXCEPTION_WEBHOOK"]);
        throw new UnauthorizedHttpException("Hmac Verification Failed!");
    }

    public function handleDispute(ShoppyModel $shoppyModel) {
        $disputeEntity = new Dispute();
        $disputeEntity
            ->setEmail($shoppyModel->getEmail())
            ->setOrderId($shoppyModel->getOrderId())
            ->setPrice($shoppyModel->getPrice())
            ->setCurrency($shoppyModel->getCurrency())
            ->setGateway($shoppyModel->getGateway())
            ->setDisputeAt(time());

        $this->entityManager->persist($disputeEntity);
        $this->entityManager->flush();
    }

    public function checkHasDisputedBefore(ShoppyModel $shoppyModel) {
        //Check if dispute was done by this gamer tag before
    }
}