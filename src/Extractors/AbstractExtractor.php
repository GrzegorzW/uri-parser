<?php

declare(strict_types = 1);

namespace App\Extractors;

use App\Components\ComponentsInterface;

abstract class AbstractExtractor
{
    /** @var AbstractExtractor */
    protected $successor;

    public function getSuccessor()
    {
        return $this->successor;
    }

    public function setSuccessor(AbstractExtractor $extractor)
    {
        if (null === $this->getSuccessor()) {
            $this->successor = $extractor;
        } else {
            $this->successor->setSuccessor($extractor);
        }
    }

    public function process(string $subject, ComponentsInterface $components)
    {
        preg_match($this->getRegex(), $subject, $matches);

        if (count($matches)) {
            if ($matches[0] !== '') {
                $components->addComponent($this->getName(), $matches[0]);
            }
            $subject = $this->trim($subject, $matches[0]);
        }

        if (null !== $this->successor) {
            $this->successor->process($subject, $components);
        }
    }

    abstract protected function getRegex(): string;

    abstract protected function getName(): string;

    abstract protected function trim(string $subject, string $match): string;

}
