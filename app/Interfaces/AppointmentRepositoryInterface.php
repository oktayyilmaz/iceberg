<?php
/**
 * iceberg - AppointmentRepositoryInterface.php
 * Initial version by: yilmazoktay124@gmail.com
 * Initial version created on: 3.12.2021
 */

namespace App\Interfaces;


interface AppointmentRepositoryInterface
{

    public function createAppointment(array $appointmentDetail);
    public function getAppointmentByDate(string $appointmentStartDate,string $appointmentFinishDate);
    public function deleteAppointment(int $appointmentId);
    public function updateAppointment(int $appointmentId,array $appointmentDetail);
    public function allAppointment();
    public function getAppointmentById(int $appointmentId);
}
