<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use function array_keys;
use function date;
use function implode;

/**
 * array $fields  = [
 *     'field_name_1' = 'field_value_1',
 *     'field_name_1' = 'field_value_1',
 *     'field_name_1' = 'field_value_1',
 * ];
 * @example $sql = $this->createInsertSQL('table_name', ['f1' => 'v1', 'f2' => 'va']);
 */
trait SqlMigrationTrait
{
    private function createInsertSQL(
        string $table,
        array &$data,
        ?bool $isCreatedAt = false,
        ?bool $isUpdatedAt = false
    ): string {
        if ($isCreatedAt) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }

        if ($isUpdatedAt) {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }

        return 'INSERT INTO `' . $table . '`
        (`' . implode('`, `', array_keys($data)) . '`)
        VALUES
        (:' . implode(', :', array_keys($data)) . ')';
    }
}
