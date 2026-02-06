<?php

namespace features\payments\strategies;

use core\interfaces\CourseBookingRepository;
use core\interfaces\FieldBookingRepository;
use core\interfaces\PaymentsRepository;
use core\interfaces\PaymentStrategy;
use PDO;
use Exception;

final class NormalPaymentStrategy implements PaymentStrategy{

	public function __construct(
		private PDO $db,
		private PaymentsRepository $paymentsRepository,
		private CourseBookingRepository $courseBookingRepo,
		private FieldBookingRepository $fieldBoolingRepo){}

	public function pay(int $userId, array $order) : array{
		try {
			$this->db->beginTransaction();

			$amount = 0;
			foreach($order as $item){
				var_dump($item);
				$booking = $this->getBookingDetails($item["tipo"], $item["booking_id"]);

				var_dump($booking);
			}
			

			//$this->paymentsRepository->insertPagamento($userId, );


			$this->db->commit();
			return [];
		}
		catch(Exception $e){
			$this->db->rollBack();
			throw $e;
		}
		
	}

	private function getBookingDetails(string $tipo, int $prenotazioneId): array {
        return match($tipo) {
            'campo' => $this->fieldBoolingRepo->getBooking($prenotazioneId),
            'corso' => $this->courseBookingRepo->getBooking($prenotazioneId),
            //'abbonamento' => $this->bookingRepository->getSubscriptionBooking($prenotazioneId),
            default => throw new Exception("Tipo prenotazione non valido: $tipo")
        };
    }
		
	public function execute(array $params): array{
		throw new Exception('Not implemented');
	}
}