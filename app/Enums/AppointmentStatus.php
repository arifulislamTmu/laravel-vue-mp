<?php

namespace App\Enums;


enum AppointmentStatus: int {

    case SCHEDULED = 1;
    case CONFIRMED = 2;
    case CLOSED = 3;

    public function color() {

        return match($this){
            AppointmentStatus::SCHEDULED => 'primary',
            AppointmentStatus::CONFIRMED => 'success',
            AppointmentStatus::CLOSED => 'danger'
        };

    }
}

