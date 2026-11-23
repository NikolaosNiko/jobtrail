<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CreateApiToken extends Command
{
	protected $signature = 'app:api-token {name=n8n : Name des Tokens}';

	protected $description = 'Erzeugt einen API-Token für n8n und ersetzt den alten';

	/**
	 * Erzeugt den Token.
	 *
	 * @return int
	 */
	public function handle(): int
	{
		$token_name = $this->argument('name');
		$email = $token_name.'@jobtrail.local';

		$user = User::whereEmail($email)
			->first();

		if ($user == null) {
			$user = new User();
			$user->setName($token_name);
			$user->setEmail($email);
			$user->setPassword(Str::random(40));
			$user->save();
		}

		$user->tokens()
			->where('name', $token_name)
			->delete();

		$token = $user->createToken($token_name);

		$this->info('Neuer API-Token (wird nur einmal angezeigt):');
		$this->line($token->plainTextToken);

		return self::SUCCESS;
	}
}
