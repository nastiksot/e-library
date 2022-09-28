<?php

declare(strict_types = 1);

namespace DoctrineMigrations;

require_once __DIR__ . '/../MailTemplatesMigrationTrait.php';

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220223111134 extends AbstractMigration
{
    use MailTemplatesMigrationTrait;

    private array $mailTemplates = [
        'dealer-request' => [
            'send_to'   => 'to@example.com',
            'send_from' => 'from@example.com',
            'locales'   => [
                'en_GB' => [
                    'subject'  => 'Your request has been sent! ',
                    'content'  => '
<p style="color:#3c4f64; font-size:22px; font-weight:100"">
    Your request has been sent!
</p>
<p style="color:#485c74; font-size:16px; line-height:22px">
    Hey %%customer_name%%,<br>
    <a href="%%dealer_link%%">%%dealer_name%%</a> successfully received your request.
</p>
',
                    'content2' => '
<p style="font-size: 16px; line-height: 20px; font-weight: 100">
    %%customer_message%%
</p>

<p>&nbsp;</p>

<p style="color:#3c4f64; font-size:14px; line-height:19px; margin-bottom:5px; margin-left:0; margin-right:0; margin-top:0">
    Name: %%customer_name%%
</p>

<p style="color:#3c4f64; font-size:14px; line-height:19px; margin-bottom:5px; margin-left:0; margin-right:0; margin-top:0">
    E-Mail:
    <a href="mailto:%%customer_email%%" style="color: #FCAC22;">%%customer_email%%</a>
</p>

<p style="color:#3c4f64; font-size:14px; line-height:19px; margin-bottom:5px; margin-left:0; margin-right:0; margin-top:0">
    Phone:
    <a href="tel:%%customer_phone%%" style="color: #FCAC22; text-decoration: none">%%customer_phone%%</a>
</p>

<p style="color:#3c4f64; font-size:14px; line-height:19px; margin-bottom:5px; margin-left:0; margin-right:0; margin-top:0">
    Address: %%customer_address%%
</p>

<p style="color:#3c4f64; font-size:14px; line-height:19px; margin-bottom:5px; margin-left:0; margin-right:0; margin-top:0; text-align:right">
    <a href="%%dealer_wish_list_link%%" style="display: inline-block; font-size: 16px; line-height: 24px; text-decoration: none; color: #FCAC22; padding: 10px 24px 8px 24px; border: 1px solid #FCAC22; border-radius: 4px">
        Link To The Configuration
    </a>
</p>
',
                ],
                'de_DE' => [
                    'subject'  => 'Ihre Anfrage wurde verschickt!',
                    'content'  => '
<p style="color:#3c4f64; font-size:22px; font-weight:100"">
    Ihre Anfrage wurde verschickt!
</p>
<p style="color:#485c74; font-size:16px; line-height:22px">
    Hallo %%customer_name%%,<br>
    <a href="%%dealer_link%%">%%dealer_name%%</a> Ihre Anfrage erfolgreich erhalten.
</p>
',
                    'content2' => '
<p style="font-size: 16px; line-height: 20px; font-weight: 100">
    %%customer_message%%
</p>

<p>&nbsp;</p>

<p style="color:#3c4f64; font-size:14px; line-height:19px; margin-bottom:5px; margin-left:0; margin-right:0; margin-top:0">
    Name: %%customer_name%%
</p>

<p style="color:#3c4f64; font-size:14px; line-height:19px; margin-bottom:5px; margin-left:0; margin-right:0; margin-top:0">
    E-Mail:
    <a href="mailto:%%customer_email%%" style="color: #FCAC22;">%%customer_email%%</a>
</p>

<p style="color:#3c4f64; font-size:14px; line-height:19px; margin-bottom:5px; margin-left:0; margin-right:0; margin-top:0">
    Telefon:
    <a href="tel:%%customer_phone%%" style="color: #FCAC22; text-decoration: none">%%customer_phone%%</a>
</p>

<p style="color:#3c4f64; font-size:14px; line-height:19px; margin-bottom:5px; margin-left:0; margin-right:0; margin-top:0">
    Adresse: %%customer_address%%
</p>

<p style="color:#3c4f64; font-size:14px; line-height:19px; margin-bottom:5px; margin-left:0; margin-right:0; margin-top:0; text-align:right">
    <a href="%%dealer_wish_list_link%%" style="display: inline-block; font-size: 16px; line-height: 24px; text-decoration: none; color: #FCAC22; padding: 10px 24px 8px 24px; border: 1px solid #FCAC22; border-radius: 4px">
        Link zur Konfiguration
    </a>
</p>
',

                ],
            ],
        ],
    ];

    public function up(Schema $schema): void
    {
        // add templates
        foreach ($this->mailTemplates as $type => $template) {
            $this->addMailTemplate($type, $template['send_to'], $template['send_from'], $template['locales']);
        }
    }

    public function down(Schema $schema): void
    {
        // remove templates
        foreach ($this->mailTemplates as $type => $template) {
            $this->removeMailTemplate($type);
        }
    }
}
