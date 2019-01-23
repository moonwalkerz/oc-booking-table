<?php
use Telegram\Bot\Api;
use Telegram\Bot\Keyboard\Keyboard;
use MartiniMultimedia\Table\Models\Booking;

Route::post('tablebot', function() {
	
	$API_KEY = "677535432:AAFCdwr0Hvdd8TgdtR0ayJjg-lIXiG6zVWc";
	$telegram = new Api($API_KEY);

	$user_id 		= $telegram->getWebhookUpdates()->getMessage()->getFrom()->getId();
	$user_name 		= $telegram->getWebhookUpdates()->getMessage()->getFrom()->getFirstName();
	$chat_id 		= $telegram->getWebhookUpdates()->getMessage()->getChat()->getId();
	
	$user_message 	= $telegram->getWebhookUpdates()->getMessage()->getText();
	
	if ($telegram->getWebhookUpdates()->getCallbackQuery()) {
		$callback = $telegram->getWebhookUpdates()->getCallbackQuery()->getData();
		Log::info($callback);
		


		list($command,$id)=explode('-',$callback);

		if ($id>0) {
			switch ($command) {
				case 'conferma':	

				Booking::where('id',$id)->update(['status'=>1]);

				$telegram->answerCallbackQuery([
					'callback_query_id' => $telegram->getWebhookUpdates()->getCallbackQuery()->getId(),
					'text'=>'Confermato!',
					'show_alert'=>true
					]);

				$telegram->editMessageText([
					'chat_id'=> $chat_id,
					'message_id' => $telegram->getWebhookUpdates()->getMessage()->getMessageId(),
					'text'=>str_replace('Ciao, sono Phil ho appena raccolto una nuova prenotazione:',"Prenotazione confermata di",$user_message)
					]);

				break;
				case 'declina':
				Booking::where('id',$id)->update(['status'=>-1]);
				break;
			}
		}
	}

	switch ($user_message) {

		case 'Prenotazioni':

			$bookings = Booking::nextBookings()->get();

			foreach ($bookings as $booking) {
				$text="";
				$text .= "Il ".date('d/m/Y',strtotime($booking['booking_date']))." alle ";
				$text .= $booking['booking_time']." ";
				$text .= "per ".$booking['guests']." ";
				$text .= "da parte di ".$booking['name'];
				$text .= " (".$booking['phone'].")";
				
				$telegram->sendMessage([
					'chat_id' => $chat_id, 
					'text' => $text,
				]);
			}
		break;
		case '/start':

		$key=Keyboard::make (
			[
			
				'keyboard' =>[
					['Prenotazioni', 'in Sospeso']
				],
				'resize_keyboard' => true, 
				'one_time_keyboard' => false

			]
);

			$telegram->sendMessage([
			'chat_id' => $chat_id, 
			'text' => "Ciao sono Phil, sto monitorando le tue prenotazioni.",
			'reply_markup' => $key
			]);
		break;
	}	
});
