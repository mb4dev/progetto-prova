<?php

namespace features\payments\repository;

use core\exceptions\CustomException;
use core\interfaces\PaymentsRepository;
use PDO;

final class PostgrePaymentsRepository implements PaymentsRepository {
    public function __construct(private PDO $db) {}

    public function insertPagamento(int $userId, float $amount): int{
        $query = "
            INSERT INTO centro_sportivo.pagamenti (user_id, totale) 
            VALUES (:userId, :amount)
            RETURNING id";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":userId", $userId);
        $stmt->bindParam(":amount", $amount);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC)['id'];
    }

    public function insertVocePagamento(int $paymentId, string $type, $amount, int $resourceId): int{
        $resourceIdColumn = match($type){
            "abbonamento" => "abbonamento_utente_id",
            "campo" => "prenotazione_campo_id",
            "corso" => "prenotazione_corso_id",
            default => throw new CustomException("tipo voce pagamento sconosciuto", 400)
        };

        $query = "
            INSERT INTO centro_sportivo.voci_pagamento (pagamento_id, tipo, importo, $resourceIdColumn) 
            VALUES (:paymentId, :type, :amount, :resourceId)
            RETURNING id
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":paymentId", $paymentId);
        $stmt->bindParam(":type", $type);
        $stmt->bindParam(":amount", $amount);
        $stmt->bindParam(":resourceId", $resourceId);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC)['id'];
    }

    public function getOrderFromPayment(int $paymentId): array{
        $query = "
        SELECT
            vp.*, 
            COALESCE(pca.slot_start, pco.slot_start) AS slot_start,     
            COALESCE(pca.user_id, pco.user_id, au.user_id) AS user_id,
            COALESCE(pca.data, pco.data) AS data
        FROM voci_pagamento vp
            LEFT JOIN prenotazioni_campi pca ON vp.prenotazione_campo_id = pca.id AND vp.tipo = 'campo'
            LEFT JOIN prenotazioni_corsi pco ON vp.prenotazione_corso_id = pco.id AND vp.tipo = 'corso'
            LEFT JOIN abbonamenti_utenti au ON vp.abbonamento_utente_id = au.id AND vp.tipo = 'abbonamento'";
        
        throw new \Exception('Not implemented');
    }       
}
