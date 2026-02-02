<?php 
/*
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

	public function getBookingsForUser(int $userId){
		
		$query = "
			SELECT 
				pr.user_id,
				pr.data,
				pr.slot_start,
				pa.totale,
				pa.id,
				pr.stato
			FROM prenotazioni pr
			LEFT JOIN pagamenti_prenotazioni pp 
				ON pr.id = pp.prenotazione_id
			LEFT JOIN pagamenti pa 
				ON pp.pagamento_id = pa.id;";

		$query = "SELECT * FROM centro_sportivo.prenotazioni WHERE user_id = :userId";

		$stmt = $this->db->prepare($query);
		$stmt->bindParam(":userId", $userId);

		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $result;
	}

}
*/