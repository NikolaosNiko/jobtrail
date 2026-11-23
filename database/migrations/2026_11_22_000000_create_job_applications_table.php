<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::create('job_applications', function (Blueprint $table) {
			$table->id();
			$table->string('company');
			$table->string('position');
			$table->string('url')->nullable();
			$table->string('location')->nullable();
			$table->boolean('remote')->default(false);
			$table->unsignedInteger('salary_min')->nullable();
			$table->unsignedInteger('salary_max')->nullable();
			$table->string('status')->default('saved')->index();
			$table->date('applied_at')->nullable();
			$table->text('notes')->nullable();
			$table->timestamps();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('job_applications');
	}
};
