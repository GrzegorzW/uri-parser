<?php

declare(strict_types = 1);

namespace App\Extractors;

use App\Components\ComponentsInterface;

abstract class AbstractExtractor
{
    /**
     * @var AbstractExtractor|null
     */
    protected $successor;

    /**
     * @return AbstractExtractor|null
     */
    public function getSuccessor(): ?AbstractExtractor
    {
        return $this->successor;
    }

    /**
     * @param AbstractExtractor $extractor
     */
    public function setSuccessor(AbstractExtractor $extractor): void
    {
        if (null === $this->getSuccessor()) {
            $this->successor = $extractor;
        } else {
            $this->successor->setSuccessor($extractor);
        }
    }

    /**
     * @param string $subject
     * @param ComponentsInterface $components
     */
    public function process(string $subject, ComponentsInterface $components): void
    {
        preg_match($this->getRegex(), $subject, $matches);

        if (\count($matches)) {
            if ($matches[0] !== '') {
                $components->addComponent($this->getName(), $matches[0]);
            }
            $subject = $this->trim($subject, $matches[0]);
        }

        if (null !== $this->successor) {
            $this->successor->process($subject, $components);
        }
    }

    /**
     * @return string
     */
    abstract protected function getRegex(): string;

    /**
     * @return string
     */
    abstract protected function getName(): string;

    /**
     * @param string $subject
     * @param string $match
     *
     * @return string
     */
    abstract protected function trim(string $subject, string $match): string;
}
