<?php 

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Traits\ConsoleCodeAutoGenHelper;

class IndexMigrationCommand extends Command
{
	use ConsoleCodeAutoGenHelper;

	protected $name = 'migrate:index';

	protected $description = 'Index';

	private $migrationTables = [
		"tbl_consult", "tbl_providers", "tbl_patients", "tbl_patients_family_history", "tbl_patient_chief_complaints", "tbl_patient_diet", "tbl_patients_activity", "tbl_patients_allergy", "tbl_patients_family_history", "tbl_patients_immunisation", "tbl_patients_medical_history", "tbl_patients_social_history", "tbl_patientv_vitals_spo2", "tbl_patient_hpi", "tbl_patient_icd_10", "tbl_patient_image_orders", "tbl_patient_lab_orders", "tbl_patient_medication", "tbl_patient_surgical_history", "tbl_patient_vitals_blood_glucose", "tbl_patient_vitals_bp", "tbl_patient_vitals_cholesterol", "tbl_patient_vitals_pulse", "tbl_patient_vitals_spo2", "tbl_patient_vitals_temperature", "tbl_patient_vitals_urine", "tbl_docs", "csh_dynamic_forms", "csh_physical_examination", "csh_review_of_system", "csh_stroke_scale"
	];

	public function handle()
	{
		foreach ($this->migrationTables as $key => $table) {
			$this->info($table);
			$columns = $this->getSchemaDetails($table);

			if (empty($columns) === false) {
				if (isset($columns['patient_id']) && !$columns['patient_id']['auto_increment'] && !$columns['patient_id']['index']) {
					Schema::table($table, function (Blueprint $table) {
					    $table->foreign('patient_id')->references('patient_id')->on('tbl_patients');
					});
					$this->info($table . " => patient_id");
				}

				if (isset($columns['provider_id']) && !$columns['provider_id']['auto_increment'] && !$columns['provider_id']['index']) {
					Schema::table($table, function (Blueprint $table) {
					    $table->foreign('provider_id')->references('provider_id')->on('tbl_providers');
					});
					$this->info($table . " => provider_id");
				}

				if (isset($columns['consult_id']) && !$columns['consult_id']['auto_increment'] && !$columns['consult_id']['index']) {
					Schema::table($table, function (Blueprint $table) {
					    $table->foreign('consult_id')->references('consult_id')->on('tbl_consult');
					});
					$this->info($table . " => consult_id");
				}

				/*if (isset($columns['user_id']) && !$columns['user_id']['auto_increment'] && !$columns['user_id']['index']) {
					Schema::table($table, function (Blueprint $table) {
					    $table->foreign('user_id')->references('id')->on('users');
					});
					$this->info($table . " => user_id");
				}*/
			}
		}
	}
}