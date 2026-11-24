<?php

namespace App\Models;

use App\Enums\ApplicationStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class JobApplication extends Model
{
	use HasFactory;

	public static array $fields = [
		'id' => 'id',
		'companyName' => 'company',
		'jobTitle' => 'position',
		'jobUrl' => 'url',
		'location' => 'location',
		'isRemote' => 'remote',
		'salaryMin' => 'salary_min',
		'salaryMax' => 'salary_max',
		'status' => 'status',
		'appliedAt' => 'applied_at',
		'remindedAt' => 'reminded_at',
		'notes' => 'notes',
		'createdAt' => 'created_at',
	];

	/**
	 * Attribut-Casts.
	 *
	 * @return array<string, string>
	 */
	protected function casts(): array
	{
		return [
			self::$fields['status'] => ApplicationStatus::class,
			self::$fields['isRemote'] => 'boolean',
			self::$fields['appliedAt'] => 'date',
			self::$fields['remindedAt'] => 'datetime',
		];
	}

	// Custom Functions

	/**
	 * Verschiebt die Bewerbung in einen Status.
	 *
	 * @param ApplicationStatus $status
	 * @return void
	 */
	public function moveTo(ApplicationStatus $status): void
	{
		$this->setStatus($status);
		$this->fillMissingAppliedAt();
		$this->save();
	}

	/**
	 * Setzt fehlendes Bewerbungsdatum.
	 *
	 * @return void
	 */
	public function fillMissingAppliedAt(): void
	{
		if ($this->getStatus() == ApplicationStatus::Applied && $this->getAppliedAt() == null) {
			$this->setAppliedAt(Carbon::now());
		}
	}

	/**
	 * Markiert die Bewerbung als erinnert.
	 *
	 * @return void
	 */
	public function markReminded(): void
	{
		$this->setRemindedAt(Carbon::now());
		$this->save();
	}

	// Relations

	// Scopes

	/**
	 * Neueste Bewerbungen zuerst.
	 *
	 * @param Builder $query
	 * @return Builder
	 */
	public function scopeNewestFirst(Builder $query): Builder
	{
		return $query->orderByDesc(self::$fields['createdAt']);
	}

	/**
	 * Bewerbungen, bei denen nachgefasst werden sollte.
	 *
	 * @param Builder $query
	 * @param int $days
	 * @return Builder
	 */
	public function scopeFollowUpDue(Builder $query, int $days = 14): Builder
	{
		return $query->where(self::$fields['status'], ApplicationStatus::Applied)
			->whereDate(self::$fields['appliedAt'], '<=', Carbon::now()->subDays($days))
			->whereNull(self::$fields['remindedAt']);
	}

	/**
	 * Filtert nach Link zur Anzeige.
	 *
	 * @param Builder $query
	 * @param string $job_url
	 * @return Builder
	 */
	public function scopeWhereJobUrl(Builder $query, string $job_url): Builder
	{
		return $query->where(self::$fields['jobUrl'], $job_url);
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
	 * Liefert den Firmennamen.
	 *
	 * @return string|null
	 */
	public function getCompanyName(): ?string
	{
		return $this->getAttribute(self::$fields['companyName']);
	}

	/**
	 * Setzt den Firmennamen.
	 *
	 * @param string $company_name
	 * @return void
	 */
	public function setCompanyName(string $company_name): void
	{
		$this->setAttribute(self::$fields['companyName'], $company_name);
	}

	/**
	 * Liefert den Stellentitel.
	 *
	 * @return string|null
	 */
	public function getJobTitle(): ?string
	{
		return $this->getAttribute(self::$fields['jobTitle']);
	}

	/**
	 * Setzt den Stellentitel.
	 *
	 * @param string $job_title
	 * @return void
	 */
	public function setJobTitle(string $job_title): void
	{
		$this->setAttribute(self::$fields['jobTitle'], $job_title);
	}

	/**
	 * Liefert den Link zur Anzeige.
	 *
	 * @return string|null
	 */
	public function getJobUrl(): ?string
	{
		return $this->getAttribute(self::$fields['jobUrl']);
	}

	/**
	 * Setzt den Link zur Anzeige.
	 *
	 * @param string|null $job_url
	 * @return void
	 */
	public function setJobUrl(?string $job_url): void
	{
		$this->setAttribute(self::$fields['jobUrl'], $job_url);
	}

	/**
	 * Liefert den Ort.
	 *
	 * @return string|null
	 */
	public function getLocation(): ?string
	{
		return $this->getAttribute(self::$fields['location']);
	}

	/**
	 * Setzt den Ort.
	 *
	 * @param string|null $location
	 * @return void
	 */
	public function setLocation(?string $location): void
	{
		$this->setAttribute(self::$fields['location'], $location);
	}

	/**
	 * Gibt an, ob Remote möglich ist.
	 *
	 * @return bool
	 */
	public function getIsRemote(): bool
	{
		return (bool) $this->getAttribute(self::$fields['isRemote']);
	}

	/**
	 * Setzt die Remote-Option.
	 *
	 * @param bool $is_remote
	 * @return void
	 */
	public function setIsRemote(bool $is_remote): void
	{
		$this->setAttribute(self::$fields['isRemote'], $is_remote);
	}

	/**
	 * Liefert das Mindestgehalt.
	 *
	 * @return int|null
	 */
	public function getSalaryMin(): ?int
	{
		return $this->getAttribute(self::$fields['salaryMin']);
	}

	/**
	 * Setzt das Mindestgehalt.
	 *
	 * @param int|null $salary_min
	 * @return void
	 */
	public function setSalaryMin(?int $salary_min): void
	{
		$this->setAttribute(self::$fields['salaryMin'], $salary_min);
	}

	/**
	 * Liefert das Maximalgehalt.
	 *
	 * @return int|null
	 */
	public function getSalaryMax(): ?int
	{
		return $this->getAttribute(self::$fields['salaryMax']);
	}

	/**
	 * Setzt das Maximalgehalt.
	 *
	 * @param int|null $salary_max
	 * @return void
	 */
	public function setSalaryMax(?int $salary_max): void
	{
		$this->setAttribute(self::$fields['salaryMax'], $salary_max);
	}

	/**
	 * Liefert den Status.
	 *
	 * @return ApplicationStatus|null
	 */
	public function getStatus(): ?ApplicationStatus
	{
		return $this->getAttribute(self::$fields['status']);
	}

	/**
	 * Setzt den Status.
	 *
	 * @param ApplicationStatus $status
	 * @return void
	 */
	public function setStatus(ApplicationStatus $status): void
	{
		$this->setAttribute(self::$fields['status'], $status);
	}

	/**
	 * Liefert das Bewerbungsdatum.
	 *
	 * @return Carbon|null
	 */
	public function getAppliedAt(): ?Carbon
	{
		return $this->getAttribute(self::$fields['appliedAt']);
	}

	/**
	 * Setzt das Bewerbungsdatum.
	 *
	 * @param Carbon|string|null $applied_at
	 * @return void
	 */
	public function setAppliedAt(Carbon|string|null $applied_at): void
	{
		$this->setAttribute(self::$fields['appliedAt'], $applied_at);
	}

	/**
	 * Liefert das Erinnerungsdatum.
	 *
	 * @return Carbon|null
	 */
	public function getRemindedAt(): ?Carbon
	{
		return $this->getAttribute(self::$fields['remindedAt']);
	}

	/**
	 * Setzt das Erinnerungsdatum.
	 *
	 * @param Carbon|null $reminded_at
	 * @return void
	 */
	public function setRemindedAt(?Carbon $reminded_at): void
	{
		$this->setAttribute(self::$fields['remindedAt'], $reminded_at);
	}

	/**
	 * Liefert die Notizen.
	 *
	 * @return string|null
	 */
	public function getNotes(): ?string
	{
		return $this->getAttribute(self::$fields['notes']);
	}

	/**
	 * Setzt die Notizen.
	 *
	 * @param string|null $notes
	 * @return void
	 */
	public function setNotes(?string $notes): void
	{
		$this->setAttribute(self::$fields['notes'], $notes);
	}

	/**
	 * Liefert das Erstellungsdatum.
	 *
	 * @return Carbon|null
	 */
	public function getCreatedAt(): ?Carbon
	{
		return $this->getAttribute(self::$fields['createdAt']);
	}
}
