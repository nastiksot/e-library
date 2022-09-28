<?php

declare(strict_types = 1);

namespace DoctrineMigrations;

/**
 *
 * @example
 *         private array $mailTemplates = [
 *              'dealer-request' => [
 *                  'send_to'   => 'to@localhost,to2@localhost',
 *                  'send_from' => 'from@localhost',
 *                  'locales'   => [
 *                      'en_GB' => ['subject' => 'subject-en_GB', 'content' => 'content-en_GB'],
 *                      'de_DE' => ['subject' => 'subject-de_DE', 'content' => 'content-de_DE'],
 *                  ],
 *              ],
 *          ];
 *
 *         foreach ($this->mailTemplates as $type => $template) {
 *              $this->addMailTemplate($type, $template['send_to'], $template['send_from'], $template['locales']);
 *         }
 *
 **/
trait MailTemplatesMigrationTrait
{

    public function addMailTemplate(string $type, string $sendTo, string $sendFrom, array $locales): void
    {
        $templateId = $this->saveMailTemplate($type, $sendTo, $sendFrom);

        foreach ($locales as $locale => $values) {
            $this->saveMailTemplateTranslation(
                $templateId,
                $type,
                $locale,
                $values['subject'],
                $values['content'],
                $values['content2'] ?? null,
                $values['subject2'] ?? null,
                $values['content3'] ?? null,
            );
        }
    }

    private function saveMailTemplate(
        string $type,
        string $sendTo,
        string $sendFrom,
    ): int {
        $sql        = 'SELECT `id` FROM `mail_templates` WHERE `type` = :type';
        $templateId = (int) $this->connection->executeQuery($sql, ['type' => $type])->fetchOne();

        // template not found save new one
        if (0 === $templateId) {
            $insertSlq = 'INSERT INTO `mail_templates`
                SET
                    `type` = :type,
                    `send_to` = :send_to,
                    `send_from` = :send_from,
                    `active` = :active,
                    `created_at` = :created_at,
                    `updated_at` = :updated_at';

            $this->connection->executeStatement($insertSlq,
                [
                    'type'       => $type,
                    'send_to'    => $sendTo,
                    'send_from'  => $sendFrom,
                    'active'     => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]
            );

            return (int) $this->connection->lastInsertId();
        }

        // template exists
        // update
        $updateSql = '
            UPDATE `mail_templates`
                SET
                    `send_to` = :send_to,
                    `send_from` = :send_from,
                    `updated_at` = :updated_at
                WHERE
                    `id` = :id
        ';

        $this->connection->executeStatement(
            $updateSql,
            [
                'id'         => $templateId,
                'send_to'    => $sendTo,
                'send_from'  => $sendFrom,
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        );

        return $templateId;
    }

    private function saveMailTemplateTranslation(
        int $templateId,
        string $type,
        string $locale,
        string $subject,
        string $content,
        ?string $content2 = null,
        ?string $subject2 = null,
        ?string $content3 = null,
    ): int {
        $sql           = 'SELECT `id` FROM `mail_template_translations` WHERE `locale` = :locale AND `type` = :type';
        $params        = ['locale' => $locale, 'type' => $type];
        $translationId = (int) $this->connection->executeQuery($sql, $params)->fetchOne();

        // template not found save new one
        if (0 === $translationId) {
            $insertSlq = 'INSERT INTO `mail_template_translations`
                SET
                    `type` = :type,
                    `locale` = :locale,
                    `translatable_id` = :translatable_id,
                    `subject` = :subject,
                    `content` = :content,
                    `content2` = :content2,
                    `subject2` = :subject2,
                    `content3` = :content3,
                    `created_at` = :created_at,
                    `updated_at` = :updated_at';

            $this->connection->executeStatement($insertSlq,
                [
                    'type'            => $type,
                    'locale'          => $locale,
                    'translatable_id' => $templateId,
                    'subject'         => $subject,
                    'content'         => $content,
                    'content2'        => $content2,
                    'subject2'        => $subject2,
                    'content3'        => $content3,
                    'created_at'      => date('Y-m-d H:i:s'),
                    'updated_at'      => date('Y-m-d H:i:s'),
                ]
            );

            return (int) $this->connection->lastInsertId();
        }

        // template exists
        // update
        $updateSql = '
            UPDATE `mail_template_translations`
                SET
                    `subject` = :subject,
                    `content` = :content,
                    `content2` = :content2,
                    `subject2` = :subject2,
                    `content3` = :content3,
                    `updated_at` = :updated_at
                WHERE
                    `id` = :id
        ';

        $this->connection->executeStatement(
            $updateSql,
            [
                'id'              => $translationId,
                'translatable_id' => $templateId,
                'subject'         => $subject,
                'content'         => $content,
                'content2'        => $content2,
                'subject2'        => $subject2,
                'content3'        => $content3,
                'updated_at'      => date('Y-m-d H:i:s'),
            ]
        );

        return $translationId;
    }

    public function removeMailTemplate(string $type): void
    {
        $sql = 'DELETE FROM `mail_templates` WHERE `type` = :type';
        $this->connection->executeStatement($sql, ['type' => $type]);
    }
}
