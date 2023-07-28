<?php

namespace App\Http\Controllers;

use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
  public  function getClients() {

       return $clients = Client::latest()->get();

    }

    public function getStatusCount(){

        $appointments = Appointment::query()
        ->when(request('status') === 'Schedule', function ($query) {
            $query->where('status', AppointmentStatus::SCHEDULED);
        })
        ->when(request('status') === 'Confirmed', function ($query) {
            $query->where('status', AppointmentStatus::CONFIRMED);
        })
        ->when(request('status') === 'Canceled', function ($query) {
            $query->where('status', AppointmentStatus::CLOSED);
        })
        ->count() ;
        return response()->json([
            'data' => $appointments,
        ]) ;

    }
}
