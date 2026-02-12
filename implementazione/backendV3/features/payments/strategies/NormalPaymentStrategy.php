<?php

namespace features\payments\strategies;

use core\exceptions\CustomException;
use core\interfaces\CourseBookingRepository;
use core\interfaces\FieldBookingRepository;
use core\interfaces\PaymentsRepository;
use core\interfaces\PaymentStrategy;
use PDO;
use Exception;
use features\booking\BookingState;

final class NormalPaymentStrategy implements PaymentStrategy
{
    public function __construct(
        private PDO $db,
        private PaymentsRepository $paymentsRepository,
        private CourseBookingRepository $courseBookingRepo,
        private FieldBookingRepository $fieldBoolingRepo,
    ) {}

    public function pay(int $userId, float $total, array $order): array
    {
        try {
            $this->db->beginTransaction();
            $amount = 0;
            $vociPagamento = [];
            foreach ($order as $item) {
                $prenotazioneId = $item["prenotazione_id"];
                $booking = $this->getBookingDetails(
                    $item["tipo"],
                    $prenotazioneId,
                );
                var_dump($booking);
                if ($booking["stato"] !== BookingState::CART->value) {
                    throw new CustomException(
                        "prenotazione $prenotazioneId pagata/cancellata",
                        400,
                    );
                }
                if ($booking["user_id"] !== $userId) {
                    throw new CustomException(
                        "prenotazione non autorizzata",
                        400,
                    );
                }

                $amount += $booking["price"];

                $vociPagamento[] = [
                    "tipo" => $item["tipo"],
                    "importo" => $booking["price"],
                    "prenotazione_id" => $prenotazioneId,
                ];
            }

            if ($amount !== $total) {
                throw new CustomException("errore calcolo totale", 400);
            }

            $paymentId = $this->paymentsRepository->insertPagamento(
                $userId,
                $amount,
            );

            foreach ($vociPagamento as $voce) {
                $this->paymentsRepository->insertVocePagamento(
                    $paymentId,
                    $voce["tipo"],
                    $voce["importo"],
                    $voce["prenotazione_id"],
                );
            }

            $updated = $this->confirmBooking($item["tipo"], $prenotazioneId);
            if (!$updated) {
                throw new CustomException(
                    "errore aggiornamento prenotazione",
                    500,
                );
            }
            $this->db->commit();
            return [];
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    private function getBookingDetails(string $tipo, int $prenotazioneId): array
    {
        return match ($tipo) {
            "campo" => $this->fieldBoolingRepo->getBooking($prenotazioneId),
            "corso" => $this->courseBookingRepo->getBooking($prenotazioneId),
            //'abbonamento' => $this->bookingRepository->getSubscriptionBooking($prenotazioneId),
            default => throw new Exception(
                "Tipo prenotazione non valido: $tipo",
            ),
        };
    }

    private function confirmBooking(string $tipo, int $prenotazioneId): bool
    {
        $state = BookingState::CONFIRMED;
        return match ($tipo) {
            "campo" => $this->fieldBoolingRepo->updateBooking(
                $prenotazioneId,
                $state,
            ),
            "corso" => $this->courseBookingRepo->updateBooking(
                $prenotazioneId,
                $state,
            ),
        };
    }

    public function execute(array $params): array
    {
        throw new Exception("Not implemented");
    }
}
