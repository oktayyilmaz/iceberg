<?php
/**
 * iceberg - AppointmentRepository.php
 * Initial version by: yilmazoktay124@gmail.com
 * Initial version created on: 3.12.2021
 */

namespace App\Repositories;


use App\Interfaces\AppointmentRepositoryInterface;
use App\Models\Appointment;

class AppointmentRepository implements AppointmentRepositoryInterface
{
    public function createAppointment($appointmentDetail)
    {
        return Appointment::create($appointmentDetail);
    }

    public function getAppointmentByDate(string $appointmentStartDate, string $appointmentFinishDate)
    {
        return Appointment::whereTime('created_at', '>', $appointmentStartDate)->whereTime('created_at', '<', $appointmentFinishDate)->get();
    }

    public function deleteAppointment(int $appointmentId)
    {
        return Appointment::destroy($appointmentId);
    }

    public function updateAppointment(int $appointmentId, array $appointmentDetail)
    {
        return Appointment::whereId($appointmentId)->update($appointmentDetail);
    }

    public function allAppointment()
    {
        return Appointment::with('contact')->get();
    }

    public function getAppointmentById(int $appointmentId){
        return Appointment::with('contact')->findOrFail($appointmentId);
    }

}
