<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
	/** @use HasFactory<UserFactory> */
	use HasApiTokens, HasFactory, Notifiable;

	public static array $fields = [
		'id' => 'id',
		'name' => 'name',
		'email' => 'email',
		'password' => 'password',
		'emailVerifiedAt' => 'email_verified_at',
	];

	/**
	 * Attribut-Casts.
	 *
	 * @return array<string, string>
	 */
	protected function casts(): array
	{
		return [
			self::$fields['emailVerifiedAt'] => 'datetime',
			self::$fields['password'] => 'hashed',
		];
	}

	// Custom Functions

	// Relations

	// Scopes

	/**
	 * Filtert nach E-Mail-Adresse.
	 *
	 * @param Builder $query
	 * @param string $email
	 * @return Builder
	 */
	public function scopeWhereEmail(Builder $query, string $email): Builder
	{
		return $query->where(self::$fields['email'], $email);
	}

	// Get und Set

	/**
	 * Liefert die ID.
	 *
	 * @return int|null
	 */
	public function getId(): ?int
	{
		return $this->getAttribute(self::$fields['id']);
	}

	/**
	 * Liefert den Namen.
	 *
	 * @return string|null
	 */
	public function getName(): ?string
	{
		return $this->getAttribute(self::$fields['name']);
	}

	/**
	 * Setzt den Namen.
	 *
	 * @param string $name
	 * @return void
	 */
	public function setName(string $name): void
	{
		$this->setAttribute(self::$fields['name'], $name);
	}

	/**
	 * Liefert die E-Mail-Adresse.
	 *
	 * @return string|null
	 */
	public function getEmail(): ?string
	{
		return $this->getAttribute(self::$fields['email']);
	}

	/**
	 * Setzt die E-Mail-Adresse.
	 *
	 * @param string $email
	 * @return void
	 */
	public function setEmail(string $email): void
	{
		$this->setAttribute(self::$fields['email'], $email);
	}

	/**
	 * Setzt das Passwort.
	 *
	 * @param string $password
	 * @return void
	 */
	public function setPassword(string $password): void
	{
		$this->setAttribute(self::$fields['password'], $password);
	}
}
