<?php 

namespace features\booking\fields;

use core\exceptions\CustomException;
use core\interfaces\BookingRepository;
use features\booking\BookingState;
use features\resources\ResourceType;
use PDO;
use PDOException;

final class FieldBookingRepository implements BookingRepository {

	public function __construct(private PDO $db){}
	public function getBooking(int $resourceId, string $date){
		$query = "
			SELECT slot_start 
			FROM centro_sportivo.prenotazioni 
			WHERE tipo = 'campo' 
				AND campo_id = :resourceId
				AND data= :date
				AND stato in ('carrello', 'confermata')" ; 

		$stmt = $this->db->prepare($query);
		$stmt->bindParam(":resourceId", $resourceId);
		$stmt->bindParam(":date", $date);

		$stmt->execute();
	
		$result = $stmt->fetchAll(PDO::FETCH_COLUMN);

		return $result;
	}

	public function insertBooking(int $userId, int $resourceId, string $date, string $slot){
		try {
			$query = '
				INSERT INTO centro_sportivo.prenotazioni (user_id, tipo, campo_id, corso_id, data, slot_start, stato, quantity) 
				VALUES (?, ?, ?, ?, ?, ?, ?, ?)
				RETURNING id';
			$stmt = $this->db->prepare($query);
			$stmt->execute([$userId, ResourceType::FIELD->value, $resourceId, null, $date, $slot, BookingState::CART->value, 1]);
	
			$result = $stmt->fetch(PDO::FETCH_ASSOC);  
			return $result['id'];  
		}
		catch(PDOException $e){
			if($e->getCode() === "23505"){
				throw new CustomException("campo $resourceId già prenotato il $date alle $slot", 409);
			}
		}	
	}

}