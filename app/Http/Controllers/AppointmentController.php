<?php

namespace App\Http\Controllers;

use App\Helper\AppointmentHelper;
use App\Helper\ResponseHelpers;
use App\Http\Requests\AppointmentCreateRequest;
use App\Http\Requests\AppointmentFilterRequest;
use App\Http\Requests\AppointmentUpdateRequest;
use App\Http\Requests\ContactCreateRequest;
use App\Http\Requests\ContactUpdateRequest;
use App\Interfaces\AppointmentRepositoryInterface;
use App\Interfaces\ContactRepositoryInterface;
use Exception;

class AppointmentController extends Controller
{
    use ResponseHelpers;

    /**
     * @var ContactRepositoryInterface
     */
    private ContactRepositoryInterface $contactRepository;
    /**
     * @var AppointmentRepositoryInterface
     */
    private AppointmentRepositoryInterface $appointmentRepository;

    /**
     * AppointmentController constructor.
     * @param ContactRepositoryInterface $contactRepository
     * @param AppointmentRepositoryInterface $appointmentRepository
     */
    public function __construct(ContactRepositoryInterface $contactRepository, AppointmentRepositoryInterface $appointmentRepository)
    {
        $this->appointmentRepository = $appointmentRepository;
        $this->contactRepository = $contactRepository;
    }

    /**
     * @param AppointmentFilterRequest $appointmentFilterRequest
     * @return mixed
     */
    public function index(AppointmentFilterRequest $appointmentFilterRequest)
    {


        $this->appointmentRepository
            ->getAppointmentByDate($appointmentFilterRequest->safe()->start_datetime, $appointmentFilterRequest->safe()->end_datetime);

        return $this->response($this->appointmentRepository->allAppointment());
    }

    /**
     * @param AppointmentCreateRequest $appointmentRequest
     * @param ContactCreateRequest $contactRequest
     * @return mixed
     * @throws Exception
     */

    public function store(AppointmentCreateRequest $appointmentRequest, ContactCreateRequest $contactRequest)
    {


        $distanceCalculate = AppointmentHelper::distanceCalculate($appointmentRequest->safe()->address,
            \Carbon\Carbon::createFromFormat('Y-m-d H:i', $appointmentRequest->safe()->datetime)
        );
        $contact = $this->contactRepository->firstOrCreateContact([
            'email' => $contactRequest->safe()->email,
        ], $contactRequest->validated());
        $appointment = $this->appointmentRepository->createAppointment(array_merge($appointmentRequest->validated(), [
            'contact_id' => $contact->id,
            'distance' => $distanceCalculate->distance,
            'office_check_out_at' => $distanceCalculate->officeCheckOutAt,
            'office_check_in_at' => $distanceCalculate->officeCheckInAt,
        ]));
        $appointment->contact = $contact;

        return $this->response($appointment);
    }

    /**
     * @param AppointmentUpdateRequest $appointmentUpdateRequest
     * @param ContactUpdateRequest $contactUpdateRequest
     * @param $appointmentId
     * @return mixed
     * @throws Exception
     */
    public function update(AppointmentUpdateRequest $appointmentUpdateRequest, ContactUpdateRequest $contactUpdateRequest, $appointmentId)
    {


        $appointmentDetail = $this->appointmentRepository->getAppointmentById($appointmentId);
        $appointmentUpdateData = $appointmentUpdateRequest->validated();

        if ($appointmentUpdateRequest->has('address') || $appointmentUpdateRequest->has('datetime')) {
            $distanceCalculate = AppointmentHelper::distanceCalculate(
                $appointmentUpdateRequest->safe()->address ?? $appointmentDetail->address,
                $appointmentUpdateRequest->has('datetime')
                    ? \Carbon\Carbon::createFromFormat('Y-m-d H:i', $appointmentUpdateRequest->safe()->datetime)
                    : $appointmentDetail->datetime
            );
            $appointmentUpdateData = array_merge($appointmentUpdateData, [
                'distance' => $distanceCalculate->distance,
                'office_check_out_at' => $distanceCalculate->officeCheckOutAt,
                'office_check_in_at' => $distanceCalculate->officeCheckInAt,
            ]);
        }

        if ($this->appointmentRepository->updateAppointment($appointmentId, $appointmentUpdateData) == 0) {
            return $this->response(['message' => 'Not new data'], 422);
        }
        $this->contactRepository->updateContact($appointmentDetail->contact_id, $contactUpdateRequest->validated());

        return $this->response($this->appointmentRepository->getAppointmentById($appointmentId));
    }

    /**
     * @note Sadece randevu bilgileri silindi, müşteri bilgileri bilerek silinmedi nedeni ise müşteri ile başka randevularıda olabilir.
     * @param int $id
     * @return mixed
     */
    public function destroy(int $id)
    {
        if ($this->appointmentRepository->deleteAppointment($id) == 0)
            return $this->response(['message' => 'Not found'], 404);

        return $this->response(['message' => 'Success Delete']);

    }
}
