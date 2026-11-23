<?php

namespace App\Enums;

enum ApplicationStatus: string
{
	case Saved = 'saved';
	case Applied = 'applied';
	case Interview = 'interview';
	case Offer = 'offer';
	case Rejected = 'rejected';

	/**
	 * Deutscher Anzeigename.
	 *
	 * @return string
	 */
	public function label(): string
	{
		return match ($this) {
			self::Saved => 'Gemerkt',
			self::Applied => 'Beworben',
			self::Interview => 'Gespräch',
			self::Offer => 'Angebot',
			self::Rejected => 'Absage',
		};
	}

	/**
	 * Prüft, ob der Prozess abgeschlossen ist.
	 *
	 * @return bool
	 */
	public function isClosed(): bool
	{
		return $this == self::Offer || $this == self::Rejected;
	}

	/**
	 * Alle Status als Auswahlliste.
	 *
	 * @return array<string, string>
	 */
	public static function options(): array
	{
		$options = [];

		foreach (self::cases() as $status) {
			$options[$status->value] = $status->label();
		}

		return $options;
	}
}
