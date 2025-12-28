<?php declare(strict_types=1);

namespace AIDemoData\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;
use Shopware\Core\Framework\Uuid\Uuid;

/**
 * @internal
 */
class Migration1766949424 extends MigrationStep
{
    public const TAG_NAME = 'AI Demo Data';

    public function getCreationTimestamp(): int
    {
        return 1766949424;
    }

    public function update(Connection $connection): void
    {
        $this->createTagIfNotExists($connection);
    }

    private function createTagIfNotExists(Connection $connection): void
    {
        // Check if tag already exists
        $existingTag = $connection->fetchOne(
            'SELECT id FROM tag WHERE name = :name',
            ['name' => self::TAG_NAME]
        );

        if ($existingTag !== false) {
            return;
        }

        // Create the tag
        $connection->insert('tag', [
            'id' => Uuid::randomBytes(),
            'name' => self::TAG_NAME,
            'created_at' => (new \DateTime())->format('Y-m-d H:i:s.u'),
        ]);
    }
}
