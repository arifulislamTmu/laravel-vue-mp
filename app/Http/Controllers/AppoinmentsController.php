<?php

namespace App\Http\Controllers;

use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppoinmentsController extends Controller
{
    public function index()
    {

      $appointment = Appointment::query()
            ->with('client:id,first_name,last_name')
            ->when(request('status'), function ($query){
                return $query->where('status' , AppointmentStatus::from(request('status')));
            })
            ->latest()
            ->paginate()
            ->through(fn($appointment) =>[
                'id' => $appointment->id,
                'start_time' => $appointment->start_time->format('Y-m-d h:i A'),
                'end_time' => $appointment->end_time->format('Y-m-d h:i A'),
                'status' => [
                    'name' => $appointment->status->name,
                    'color' => $appointment->status->color(),
                ],
                'client' => $appointment->client,
                'title' => $appointment->title,
            ]);

      $statusCounts = Appointment::selectRaw('
            COUNT(*) AS total_count,
            SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) AS schedule_count,
            SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) AS confirm_count,
            SUM(CASE WHEN status = 3 THEN 1 ELSE 0 END) AS canceled_count
        ')->first();

        return response()->json([
            'data' => $appointment,
            'totalCount' => $statusCounts->total_count,
            'scheduleCount' => $statusCounts->schedule_count,
            'confirmCount' => $statusCounts->confirm_count,
            'canceledCount' => $statusCounts->canceled_count,
        ]);
    }

  public  function store(){

    //dd(request()->all());
    request()->validate([
        'title' => 'required',
        'description' => 'required',
        'client_id' => 'required',
        'start_time' => 'required',
        'end_time' => 'required'
    ]);

      Appointment::create([
        'title' => request('title'),
        'client_id' => request('client_id'),
        'start_time' => request('start_time'),
        'end_time' => request('end_time'),
        'description' => request('description'),
        'status' => AppointmentStatus::SCHEDULED

      ]);

      return response()->json([ 'message' => "success"]);

    }

    public function edit(Appointment $appointment) {

        return $appointment;
    }

    public function update(Appointment $appointment) {
       $validate = request()->validate([
            'title' => 'required',
            'description' => 'required',
            'client_id' => 'required',
            'start_time' => 'required',
            'end_time' => 'required'
        ]);

        $appointment->update($validate);
        return response()->json('success');
    }

  public  function delete(Appointment $appointment){

       $appointment->delete();
       return response()->json(['success' => true], 200);
    }


    function test() : string {
        return "hello test";
    }
}
