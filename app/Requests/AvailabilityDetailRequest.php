<?php

namespace App\Requests;

use App\Entities\AvailabilityDetail;
use Pearl\RequestValidate\RequestAbstract;

class AvailabilityDetailRequest extends RequestAbstract
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {

        $request = app('request');
        $errorRules = [];

        // Edited Rules
        if ($this->route('id')) {

            if (empty($request->from_date_time)) {
                $errorRules['from_date_time'] = 'required';   
            }

            if (empty($request->to_date_time)) {
                $errorRules['to_date_time'] = 'required';   
            }

            if (empty($request->slot_status)) {
                $errorRules['slot_status'] = 'required';   
            }

            if (empty($request->slot_type)) {
                $errorRules['slot_type'] = 'required';   
            }

            if (empty($errorRules)) {
                $available = AvailabilityDetail::editAvailabilityCheck($request);
                if (empty($available['status'])) {
                    if (!empty($available['dates'])) {
                        $errorRules['availability_dates'] = 'required';
                    } else {
                        $errorRules['availability'] = 'required';
                    }
                }
            }

        } else {
            if (empty($request->from_date)) {
                $errorRules['from_date'] = 'required';   
            }

            if (empty($request->to_date)) {
                $errorRules['to_date'] = 'required';   
            }

            if (empty($request->slot_group)) {
                $errorRules['slot_group'] = 'required';   
            }

            if (empty($request->slot_type)) {
                $errorRules['slot_type'] = 'required';   
            }

            if (empty($request->available_type)) {
                $errorRules['available_type'] = 'required';   
            }

            if (empty($request->queue_slots)) {
                $errorRules['slots'] = 'required';   
            }

            if ($request->available_type == 'weekdays' && empty($request->week_days_sun) &&
                empty($request->week_days_mon) && empty($request->week_days_tue) &&
                empty($request->week_days_wed) && empty($request->week_days_thu) &&
                empty($request->week_days_fri) && empty($request->week_days_sat)) {
                $errorRules['days_selection'] = 'required';   
            }
            if (empty($errorRules)) {
                $available = AvailabilityDetail::slotArray($request);
                if (empty($available['status'])) {
                    if (!empty($available['dates'])) {
                        $errorRules['availability_dates'] = 'required';
                    } else {
                        $errorRules['availability'] = 'required';
                    }
                }
            }
        }

        return $errorRules;

    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'from_date.required' => 'From date required',
            'to_date.required' => 'To date required',
            'slot_group.required' => 'Slot group required',
            'slot_type.required' => 'Slot type required',
            'available_type.required' => 'Available type required',
            'slots.required' => 'Slots required',
            'days_selection.required' => 'Days are required',
            'availability.required' => 'Above selected date and time is already exist.',
            'availability_dates.required' => 'Your selection between dates are not in day selection',
            'from_date_time.required' => 'From date required',
            'to_date_time.required' => 'From date required',
            'slot_status.required' => 'From date required',
        ];
    }
}


