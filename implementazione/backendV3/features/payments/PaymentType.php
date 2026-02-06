<?php

namespace features\payments;

enum PaymentType: string {
    case CARD = 'carta';
    case SUBSCRIPTION = 'abbonamento';
}
