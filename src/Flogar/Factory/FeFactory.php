<?php
declare(strict_types=1);

namespace Flogar\Factory;

use Flogar\Builder\BuilderInterface;
use Flogar\Model\DocumentInterface;
use Flogar\Model\Response\BaseResult;
use Flogar\Services\SenderInterface;
use Flogar\XMLSecLibs\Sunat\SignedXml;

/**
 * Class FeFactory.
 */
class FeFactory implements FactoryInterface
{
    /**
     * @var SignedXml|null
     */
    private $signer;

    /**
     * Sender service.
     *
     * @var SenderInterface|null
     */
    private $sender;

    /**
     * Ultimo xml generado.
     *
     * @var string|null
     */
    private $lastXml;

    /**
     * Xml Builder.
     *
     * @var BuilderInterface|null
     */
    private $builder;

    /**
     * Get document builder.
     *
     * @return BuilderInterface
     */
    public function getBuilder(): ?BuilderInterface
    {
        return $this->builder;
    }

    /**
     * Get sender service.
     *
     * @return SenderInterface
     */
    public function getSender(): ?SenderInterface
    {
        return $this->sender;
    }

    /**
     * Set sender service.
     *
     * @param SenderInterface $sender
     *
     * @return FeFactory
     */
    public function setSender(?SenderInterface $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * Set document builder.
     *
     * @param BuilderInterface $builder
     *
     * @return FeFactory
     */
    public function setBuilder(?BuilderInterface $builder): self
    {
        $this->builder = $builder;

        return $this;
    }

    /**
     * @return SignedXml
     */
    public function getSigner(): ?SignedXml
    {
        return $this->signer;
    }

    /**
     * @param SignedXml $signer
     *
     * @return FeFactory
     */
    public function setSigner(?SignedXml $signer): self
    {
        $this->signer = $signer;

        return $this;
    }

    /**
     * Build and send document.
     *
     * @param DocumentInterface $document
     *
     * @return BaseResult
     */
    public function send(DocumentInterface $document): ?BaseResult
    {
        $xml = $this->getXmlSigned($document);

        return $this->sender->send($document->getName(), $xml);
    }


    public function sendXml(?string $name, ?string $xml): ?BaseResult
    {
        return $this->sender->send($name, $xml);
    }
    
    /**
     * Get Last XML Signed.
     *
     * @return string
     */
    public function getLastXml(): ?string
    {
        return $this->lastXml;
    }

    /**
     * @param DocumentInterface $document
     *
     * @return string
     */
    public function getXmlSigned(DocumentInterface $document): ?string
    {
        $xml = $this->builder->build($document);

        $this->lastXml = $this->signer->signXml($xml);

        return $this->lastXml;
    }
}