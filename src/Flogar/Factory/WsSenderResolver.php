<?php

declare(strict_types=1);

namespace Flogar\Factory;

use Flogar\Model\Summary\Summary;
use Flogar\Model\Voided\Reversion;
use Flogar\Model\Voided\Voided;
use Flogar\Services\SenderInterface;
use Flogar\Validator\ErrorCodeProviderInterface;
use Flogar\Ws\Services\BillSender;
use Flogar\Ws\Services\SummarySender;
use Flogar\Ws\Services\WsClientInterface;

class WsSenderResolver
{
    /**
     * @var string[]
     */
    private $summary;
    /**
     * @var WsClientInterface
     */
    private $client;
    /**
     * @var ErrorCodeProviderInterface|null
     */
    private $codeProvider;

    /**
     * WsSenderResolver constructor.
     * @param WsClientInterface $client
     * @param ErrorCodeProviderInterface|null $codeProvider
     */
    public function __construct(WsClientInterface $client, ?ErrorCodeProviderInterface $codeProvider)
    {
        $this->client = $client;
        $this->codeProvider = $codeProvider;
        $this->summary = [
            Summary::class,
            Voided::class,
            Reversion::class
        ];
    }

    public function find(string $docClass): SenderInterface
    {
        $sender = in_array($docClass, $this->summary) ? new SummarySender() : new BillSender();
        $sender->setClient($this->client);
        $sender->setCodeProvider($this->codeProvider);

        return $sender;
    }
}
