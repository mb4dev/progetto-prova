<?php

final class SlotDisponibileCorso {
    public function __construct(
        public int $id,
        public int $corso_id,
        public string $data,
        public array $slot,
        public string $created_at
    ) {}
}