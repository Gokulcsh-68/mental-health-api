<?php

namespace App\Jobs;

use App\Entities\ActivityWellness;
use App\Entities\Doc;
use App\Entities\PatientHealth;
use App\Entities\PatientHistory;
use App\Entities\PhysicalExamination;
use App\Entities\ReviewOfSystem;
use App\Entities\Vital;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class FreezePatientHealthRecordJob
{
    private $_user_id;

    public function __construct($user_id)
    {
        $this->_user_id = $user_id;
    }

    public function handle()
    {
        if ($this->_user_id) {
            Vital::open()->where('user_id', $this->_user_id)
                ->update(array('freeze' => 1));
            PatientHealth::open()->where('patient_id', $this->_user_id)
                ->update(array('freeze' => 1));
            PhysicalExamination::open()->where('patient_id', $this->_user_id)
                ->update(array('freeze' => 1));
            PatientHistory::open()->where('patient_id', $this->_user_id)
                ->update(array('freeze' => 1));
            ReviewOfSystem::open()->where('patient_id', $this->_user_id)
                ->update(array('freeze' => 1));
            Doc::open()->where('user_id', $this->_user_id)
                ->update(array('freeze' => 1));
            ActivityWellness::open()->where('patient_id', $this->_user_id)
                ->update(array('freeze' => 1));

            Log::info('FreezePatientHealthRecordJob Dispatched with user');

        } else {
            $freeze_hour = config('api.health_record_freeze_hours');

            $freezing_hours = Carbon::now()->subhours($freeze_hour);

            Vital::open()->where('created_at', '<=', $freezing_hours)
                ->update(array('freeze' => 1));
            PatientHealth::open()->where('created_at', '<=', $freezing_hours)
                ->update(array('freeze' => 1));
            PhysicalExamination::open()->where('created_at', '<=', $freezing_hours)
                ->update(array('freeze' => 1));
            PatientHistory::open()->where('created_at', '<=', $freezing_hours)
                ->update(array('freeze' => 1));
            ReviewOfSystem::open()->where('created_at', '<=', $freezing_hours)
                ->update(array('freeze' => 1));
            Doc::open()->where('created_at', '<=', $freezing_hours)
                ->update(array('freeze' => 1));
            ActivityWellness::open()->where('created_at', '<=', $freezing_hours)
                ->update(array('freeze' => 1));

            Log::info('FreezePatientHealthRecordJob Dispatched without user');
        }
    }
}
