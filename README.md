# Factories
\App\Entities\User::factory()->count(5)->create();

# How HMS6500 Works
	# Basic Url for HMS to System in following Routes
	--------------------------------------------------------
	peripheral/{access_token}/deviceLogin = 'Login'
	peripheral/{access_token}/originalData = 'Vitals Storing'
	peripheral/{access_token}/physicalReport = 'ECG Files'

	# Other URLS
	--------------------------------------------------------
	peripheral/{access_token}/basicInfo
	peripheral/{access_token}/controlFile
	peripheral/{access_token}/trendData 
