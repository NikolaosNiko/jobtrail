<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	/**
	 * Fügt das Erinnerungsdatum hinzu.
	 *
	 * @return void
	 */
	public function up(): void
	{
		Schema::table('job_applications', function (Blueprint $table) {
			$table->timestamp('reminded_at')
				->nullable()
				->after('applied_at');
		});
	}

	/**
	 * Entfernt das Erinnerungsdatum.
	 *
	 * @return void
	 */
	public function down(): void
	{
		Schema::table('job_applications', function (Blueprint $table) {
			$table->dropColumn('reminded_at');
		});
	}
};
