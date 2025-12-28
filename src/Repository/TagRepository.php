<?php

namespace AIDemoData\Repository;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\Tag\TagEntity;

class TagRepository
{
    public const TAG_NAME = 'AI Demo Data';

    /**
     * @var EntityRepository
     */
    private $repository;

    /**
     * @param EntityRepository $repository
     */
    public function __construct(EntityRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Gets the AI Demo Data tag, creating it if it doesn't exist.
     *
     * @param Context $context
     * @return string The tag ID
     */
    public function ensureTagExists(Context $context): string
    {
        $existingTag = $this->findByName(self::TAG_NAME, $context);

        if ($existingTag !== null) {
            return $existingTag->getId();
        }

        return $this->createTag(self::TAG_NAME, $context);
    }

    /**
     * @param string $name
     * @param Context $context
     * @return TagEntity|null
     */
    public function findByName(string $name, Context $context): ?TagEntity
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('name', $name));

        return $this->repository->search($criteria, $context)->first();
    }

    /**
     * @param string $name
     * @param Context $context
     * @return string The created tag ID
     */
    public function createTag(string $name, Context $context): string
    {
        $id = Uuid::randomHex();

        $this->repository->create([
            [
                'id' => $id,
                'name' => $name,
            ]
        ], $context);

        return $id;
    }
}
