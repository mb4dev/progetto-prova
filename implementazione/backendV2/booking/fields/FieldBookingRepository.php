<?php 

namespace booking\fields;

use booking\BookingState;
use booking\interfaces\BookingRepository;
use core\exceptions\BookingConflictException;
use PDOException;
use resources\ResourceType;
use PDO;

final class FieldBookingRepository extends BookingRepository {

	public function getBooking(int $resourceId, string $date, string $slot){
		throw new \Exception('Not implemented');	
	}

	public function insertBooking(int $userId, int $resourceId, string $date, string $slot){
		try {
			$query = '
				INSERT INTO centro_sportivo.prenotazioni (user_id, tipo, campo_id, corso_id, data, slot_start, stato, quantity) 
				VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
			$stmt = $this->db->prepare($query);
			$stmt->execute([$userId, ResourceType::FIELD->value, $resourceId, null, $date, $slot, BookingState::CART->value, 1]);
	
			$result = $stmt->fetch(PDO::FETCH_ASSOC);  
			return $result['id'];  
		}
		catch(PDOException $e){
			if($e->getCode() === "23505"){
				throw new BookingConflictException();
			}
		}	
	}

}