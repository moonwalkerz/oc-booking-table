<?php
use Telegram\Bot\Api;
use EugeneTolok\Telegram\Models\User;
use EugeneTolok\Telegram\Models\Dialog;
use EugeneTolok\Telegram\Models\Step;
use EugeneTolok\Telegram\Models\Settings;

Route::post('bot/v3', function () {
	$API_KEY = Settings::get('tg_api_key');
	$telegram = new Api($API_KEY);
	$user_id 		= $telegram->getWebhookUpdates()->getMessage()->getFrom()->getId();
	$user_name 		= $telegram->getWebhookUpdates()->getMessage()->getFrom()->getFirstName();
	$chat_id 		= $telegram->getWebhookUpdates()->getMessage()->getChat()->getId();
	$user_message 	= $telegram->getWebhookUpdates()->getMessage()->getText();
	$user_slug = "";
	$user_last_name = "";
	try{
		$user_slug = $telegram->getWebhookUpdates()->getMessage()->getFrom()->getUsername();
	}
	catch( Exception $ErrorHandle ){
		//
	}
	try{
		$user_last_name = $telegram->getWebhookUpdates()->getMessage()->getFrom()->getLastName();
	}
	catch( Exception $ErrorHandle ){
		//
	}
	$user;
	function set_variable($variable_name, $variable_value, $user){
		$decoded_json;
		try{
			$decoded_json = json_decode($user->variables, true);
			$decoded_json[$variable_name] = $variable_value;
			$user->variables = json_encode($decoded_json);
		}
		catch( Exception $ErrorHandle ){
			$decoded_json = array($variable_name => $variable_value);
			$user->variables = json_encode($decoded_json);
		}
	}
	function get_variable($variable_name, $user){
		$decoded_json;
		try{
			$decoded_json = json_decode($user->variables, true);
			$t_r = $decoded_json[$variable_name];
			return $t_r;
		}
		catch( Exception $ErrorHandle ){
			return "The variable is not set...";
		}
	}
	function delete_variable($variable_name, $user){
		$decoded_json;
		try{
			$decoded_json = json_decode($user->variables, true);
			unset($decoded_json[$variable_name]);
			$user->variables = json_encode($decoded_json);
		}
		catch( Exception $ErrorHandle ){
			return "The variable is not set...";
		}
	}
	try{
		$user = User::where('tg_id', '=', $user_id)->where('chat_id', '=', $chat_id)->firstOrFail();
		$user->first_name = $user_name;
		$user->user_name = $user_slug;
		$user->last_name = $user_last_name;
		$user->save();
	}
	catch(Illuminate\Database\Eloquent\ModelNotFoundException $e)
	{
		$user = new User;
		$user->tg_id = $user_id;
		$user->chat_id = $chat_id;
		$user->first_name = $user_name;
		$user->dialog_id 	= 1;
		$user->step_count 	= 1;
		$user->user_name = $user_slug;
		$user->last_name = $user_last_name;
		$user->save();
		$telegram->sendMessage([
			  'chat_id' => $chat_id,
			  'text' => Settings::get('welcome_text')
			]);
	}
	try{
		if ($user_message != "/reset")
		{
			$dialog 		= Dialog::findOrFail($user->dialog_id);
			$steps_order = array_combine(range(1, count($dialog->steps_order)), array_values($dialog->steps_order));
			$step 			= Step::findOrFail($steps_order[$user->step_count]['step']);
			$next_dialog 		= "";
			$next_step			= "";
			eval($step->code);
			if ($next_dialog == "" && count($dialog->steps_order) == $user->step_count && $next_step == "")
			{
				$user->dialog_id 	= Settings::get('dialog_id');
				$user->step_count 	= Settings::get('step_id');
				$user->save();
			}
			else if($next_dialog != "" && $next_step == "")
			{
				$user->dialog_id  = $next_dialog;
				$user->step_count = Settings::get('step_id');
				$user->save();
			}
			else if($next_dialog != "" && $next_step != "")
			{
				$user->dialog_id  = $next_dialog;
				$user->step_count = $next_step;
				$user->save();
			}
			else if ($next_step != "")
			{
				$user->step_count = $next_step;
				$user->save();
			}
			else if ($next_step == "")
			{
				$user->step_count = $user->step_count + 1;
				$user->save();
			}
			if($chat_id < 0)
			{
				$other_group_users = User::where('chat_id', '=', $chat_id)->get();
				foreach ($other_group_users as $group_user) {
					$group_user->dialog_id = $user->dialog_id;
					$group_user->step_count = $user->step_count;
					$group_user->variables = $user->variables;
					$group_user->save();
				}
			}
		}
		else {
			$user->dialog_id 	= Settings::get('dialog_id');
			$user->step_count 	= Settings::get('step_id');
			$user->variables = "";
			$user->save();
			$telegram->sendMessage([
            'chat_id' => $chat_id,
            'text' => "Reset: ok."
        ]);
		}
	}
	catch(Illuminate\Database\Eloquent\ModelNotFoundException $e){
		eval(Step::find(Settings::get('step_id'))->code);
	}
	return ("it's ok");
});


Route::post('bot/sendmessage', function () {
	$API_KEY = Settings::get('tg_api_key');
	$telegram = new Api($API_KEY);
		if (isset($_POST['broadcast_type']))
		{
			$users = User::select('chat_id')->groupBy('chat_id')->get();
			foreach ($users as $key => $user) {
				try{
						$telegram->sendMessage([
			            'chat_id' => $user->chat_id,
			            'text' => $_POST['message'],
									'parse_mode' => 'HTML'
			        ]);

							if (isset($_POST['documents']))
							{
								$documents = explode(",", $_POST['documents']);
								foreach ($documents as $document) {
									$telegram->sendDocument([
												'chat_id' => $user->chat_id,
												'document' => $document
										]);
								}
							}
							if (isset($_POST['audios']))
							{
								$audios = explode(",", $_POST['audios']);
								foreach ($audios as $audio) {
									$telegram->sendAudio([
												'chat_id' => $user->chat_id,
												'audio' => $audio
										]);
								}
							}
							if (isset($_POST['photos']))
							{
								$photos = explode(",", $_POST['photos']);
								foreach ($photos as $photo) {
									$telegram->sendPhoto([
												'chat_id' => $user->chat_id,
												'photo' => $photo
										]);
								}
							}

							$group_users = User::where('chat_id', '=', $user->chat_id)->get();
							foreach ($group_users as $key => $group_user) {
								$group_user->blocked = 0;
								$group_user->save();
							}

				}
				catch( Exception $ErrorHandle ){
					$group_users = User::where('chat_id', '=', $user->chat_id)->get();
					foreach ($group_users as $key => $group_user) {
						$group_user->blocked = 1;
						$group_user->save();
					}
				}
			}
		}
		else {
			$users_ids = explode(",", $_POST['users_ids']);
			foreach ($users_ids as $key => $user_id) {
				try{
						$telegram->sendMessage([
			            'chat_id' => $user_id,
			            'text' => $_POST['message'],
									'parse_mode' => 'HTML'
			        ]);

							if (isset($_POST['documents']))
							{
								$documents = explode(",", $_POST['documents']);
								foreach ($documents as $document) {
									$telegram->sendDocument([
						            'chat_id' => $user_id,
						            'document' => $document
						        ]);
								}
							}
							if (isset($_POST['audios']))
							{
								$audios = explode(",", $_POST['audios']);
								foreach ($audios as $audio) {
									$telegram->sendAudio([
						            'chat_id' => $user_id,
						            'audio' => $audio
						        ]);
								}
							}
							if (isset($_POST['photos']))
							{
								$photos = explode(",", $_POST['photos']);
								foreach ($photos as $photo) {
									$telegram->sendPhoto([
						            'chat_id' => $user_id,
						            'photo' => $photo
						        ]);
								}
							}

							$group_users = User::where('chat_id', '=', $user_id)->get();
							foreach ($group_users as $key => $group_user) {
								$group_user->blocked = 0;
								$group_user->save();
							}
					}
				catch( Exception $ErrorHandle ){
					$group_users = User::where('chat_id', '=', $user_id)->get();
					foreach ($group_users as $key => $group_user) {
						$group_user->blocked = 1;
						$group_user->save();
					}
				}
			}
		}
		return "<script>window.history.back();</script>";
});
