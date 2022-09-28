<?php

declare(strict_types=1);

namespace App\DTO\Entity;

use Carbon\CarbonInterface;
use OpenApi\Annotations as OA;
use Symfony\Component\Serializer\Annotation\Groups;

class UserDTO
{
    /**
     * @Groups({
     *     "no-group",
     * })
     */
    private int $id;

    /**
     * @Groups({
     *     "no-group",
     * })
     */
    private string $password;

    /**
     * @Groups({
     *     "account",
     * })
     */
    private string $email;

    /**
     * @Groups({
     *     "no-group",
     * })
     * @OA\Property(type="array", @OA\Items(type="string"))
     */
    private array $roles = [];

    /**
     * @Groups({
     *     "account",
     * })
     */
    private bool $active;

    /**
     * @Groups({
     *     "no-group",
     * })
     */
    private ?bool $googleAuthenticatorEnabled = false;

    /**
     * @Groups({
     *     "no-group",
     * })
     */
    private ?string $googleAuthenticatorToken = null;

    /**
     * @Groups({
     *     "account",
     * })
     */
    private ?string $locale = null;

    /**
     * @Groups({
     *     "no-group",
     * })
     */
    private ?CarbonInterface $createdAt = null;

    /**
     * @Groups({
     *     "no-group",
     * })
     */
    private ?CarbonInterface $updatedAt = null;

    /**
     * @Groups({
     *     "account",
     * })
     */
    private ?string $firstName = null;

    /**
     * @Groups({
     *     "account",
     * })
     */
    private ?string $lastName = null;

    /**
     * @Groups({
     *     "account",
     * })
     */
    private ?bool $acceptNews = false;

    /**
     * @Groups({
     *     "account",
     * })
     */
    private ?bool $acceptProcessPersonalData = false;

    /**
     * @Groups({
     *     "account",
     * })
     */
    private ?bool $acceptPrivacyPolicy = false;

    /**
     * @Groups({
     *     "forgot_password",
     * })
     */
    private ?string $forgotPasswordToken = null;

    /**
     * @Groups({
     *     "forgot_password",
     * })
     */
    private ?CarbonInterface $forgotPasswordValidAt = null;

    /**
     * @Groups({
     *     "register_confirm",
     * })
     */
    private ?string $registerConfirmToken = null;

    /**
     * @Groups({
     *     "register_confirm",
     * })
     */
    private ?CarbonInterface $registerConfirmValidAt = null;

    public function __construct(
        int $id,
        string $password,
        string $email,
        array $roles,
        bool $active,
        ?bool $googleAuthenticatorEnabled,
        ?string $googleAuthenticatorToken,
//        ?string $locale,
        ?CarbonInterface $createdAt,
        ?CarbonInterface $updatedAt,
        ?string $firstName,
        ?string $lastName,
//        ?bool $acceptNews,
//        ?bool $acceptProcessPersonalData,
//        ?bool $acceptPrivacyPolicy,
//        ?string $forgotPasswordToken,
//        ?CarbonInterface $forgotPasswordValidAt,
//        ?string $registerConfirmToken,
//        ?CarbonInterface $registerConfirmValidAt,
    ) {
        $this->id                         = $id;
        $this->password                   = $password;
        $this->email                      = $email;
        $this->roles                      = $roles;
        $this->active                     = $active;
        $this->googleAuthenticatorEnabled = $googleAuthenticatorEnabled;
        $this->googleAuthenticatorToken   = $googleAuthenticatorToken;
        $this->locale                     = $locale;
        $this->createdAt                  = $createdAt;
        $this->updatedAt                  = $updatedAt;
        $this->firstName                  = $firstName;
        $this->lastName                   = $lastName;
//        $this->acceptNews                 = $acceptNews;
//        $this->acceptProcessPersonalData  = $acceptProcessPersonalData;
//        $this->acceptPrivacyPolicy        = $acceptPrivacyPolicy;
//        $this->forgotPasswordToken        = $forgotPasswordToken;
//        $this->forgotPasswordValidAt      = $forgotPasswordValidAt;
//        $this->registerConfirmToken       = $registerConfirmToken;
//        $this->registerConfirmValidAt     = $registerConfirmValidAt;
    }

}
