<?php
namespace MartiniMultimedia\Table\Components;

use Cms\Classes\ComponentBase;
use Input;
use Mail;
use Validator;
use ValidationException;
use Redirect;
use Flash;
use MartiniMultimedia\Table\Models\Booking;

use Carbon\Carbon;

//use EugeneTolok\Telegram\Models\Settings;
use Telegram\Bot\Api;
use \Telegram\Bot\Keyboard\Keyboard as K;

//use EugeneTolok\Telegram\Models\User;
use Log;

class BookingForm extends ComponentBase
{

	public function componentDetails(){
		return [
		'name' => 'Booking Form',
		'description' => 'Book a table form'
		];
	}


	public function defineProperties() {
		return [
			'emailto' => [
				'title' => 'Email To',
				'description' => 'Where we send the email to.',
				'default' => 'info@example.com'
			],
			'emailtoname' => [
				'title' => 'Name',
				'description' => 'Name of the email to.',
				'default' => 'Administrator'
			],
			'subject' => [
				'title' => 'Subject',
				'description' => 'Subject of the email',
				'default' => 'Message from website' 
			]

		];
	}

	public function onRun()
	{
		$this->addJs('/plugins/martinimultimedia/table/assets/js/bootstrap-datepicker.min.js');
		$this->addJs('/plugins/martinimultimedia/table/assets/locales/bootstrap-datepicker.it.min.js');
		$this->addCss('/plugins/martinimultimedia/table/assets/css/bootstrap-datepicker.css');
	}
/*

*/
	public function onSend()
	{

		$data = post();
		$rules = [
			'name'=> 'required|min:5',
			'guests' => 'required',
			'email' => 'required',
			'date' => 'required',
			'time' => 'required',
			'phone'=> 'required',
		];
		$validator = Validator::make($data,$rules);


		if ($validator->fails()){
			throw new ValidationException($validator);
		} else {

			$booking=new Booking();
			$booking->name=Input::get('name');
			$booking->guests=Input::get('guests');
			$booking->booking_date=Input::get('date');
			$booking->booking_time=Input::get('time');
			$booking->phone=Input::get('phone');		
			$booking->save();
			$id=$booking->getKey();

			$vars = [
				'name'=>Input::get('name'),
				'email'=> 'info@locandadelleterme.it',
				'guests'=>Input::get('guests'),
				'booking_date'=>Input::get('date'),
				'booking_time'=>Input::get('time'),
				'phone'=>Input::get('phone')
			];
			
			$API_KEY = "677535432:AAFCdwr0Hvdd8TgdtR0ayJjg-lIXiG6zVWc";//Settings::get('tg_api_key');
			$telegram = new Api($API_KEY);
					
			//			$users_ids = explode(",", "194123828,23567559");
			$users_ids = explode(",", "23567559,194123828");


			//$prenotazione = "Ciao, sono Phil ho appena raccolto una nuova prenotazione:" . " Nome:".$vars['name']." N° di Coperti: ".$vars['guests']." Booking date:".$vars['booking_date']." Ora: ".$vars['booking_time'].'<a href="tel:'.$vars['phone'].'">'.$vars['phone'].'</a>';
			$prenotazione = "Ciao, sono Phil ho appena raccolto una nuova prenotazione:" . "\n Nome:".$vars['name']."\n N° di Coperti: ".$vars['guests']."\n Booking date:".$vars['booking_date']."\n Ora: ".$vars['booking_time']."\n Tel".$vars['phone'];

			foreach ($users_ids as $key => $user_id) {
				try{

					$keyboard = K::make()->inline()
					->row(
						K::inlineButton(['text' => 'Conferma '.$vars['name'], 'callback_data' => 'conferma-'.$id ]),
						K::inlineButton(['text' => 'Declina', 'callback_data' => 'declina-'.$id])
					);
					$telegram->sendMessage([
						'chat_id' => $user_id,
						'text' => $prenotazione,
						'reply_markup' => $keyboard
							]);
				}
				catch( Exception $ErrorHandle ) {
				}
						
			}
		}

				Flash::success('Richiesta inviata');
			return;
			//return Redirect::back();

	//	}


	}

}


?>