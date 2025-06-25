<?php

namespace App\Enums;

enum ReservationStatus: string
{
  case ACTIVE = 'active';
  case CANCELLED = 'cancelled';
}
