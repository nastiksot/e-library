<?php

declare(strict_types=1);

namespace DoctrineMigrations;

/**
 * array $messages = [
 *     'NAVIGATION.WISH_LIST' => [
 *         'default' => 'Wish List',
 *         'locales' => [
 *             'de-DE' => 'Merkzettel',
 *             'en-GB' => 'Wish List',
 *         ],
 *     ],
 * ];
 */
trait MessagesMigrationTrait
{
    public function addMessages(array $messages): void
    {
        $messagesSelectSql = 'SELECT COUNT(*) FROM messages WHERE `code` = :code';

        $messagesSql = '
            INSERT INTO `messages`
                SET
                    `code` = :code,
                    `default_value` = :default
        ';

        $messageTranslationsSql = '
            INSERT INTO `message_translations`
                SET
                    `translatable_id` = :translatableId,
                    `locale` = :locale,
                    `value` = :value
        ';

        foreach ($messages as $code => $values) {
            $exists = (bool) $this->connection->executeQuery($messagesSelectSql, ['code' => $code])->fetchOne();

            if (!$exists) {
                $this->connection->executeStatement($messagesSql, ['code' => $code, 'default' => $values['default']]);
                $translatableId = $this->connection->lastInsertId();

                foreach ($values['locales'] as $locale => $value) {
                    $this->connection->executeStatement(
                        $messageTranslationsSql,
                        [
                            'translatableId' => $translatableId,
                            'locale'         => $locale,
                            'value'          => $value,
                        ]
                    );
                }
            }
        }
    }

    public function removeMessages(array $messages): void
    {
        $messagesSql = 'DELETE FROM `messages` WHERE `code` = :code';

        foreach ($messages as $code => $values) {
            $this->connection->executeStatement($messagesSql, ['code' => $code]);
        }
    }
}
