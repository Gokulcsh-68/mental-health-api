## Lumen Entity Package Commands

This document provides an overview of the commands used to create Lumen entities, requests, and resources, along with a combined command to generate all three at once.

# ENV
'''bash
 cp .env.example .env
'''

### Commands

1. **Create an Entity**
   ```bash
   php artisan make:entity sample
   ```
   This command generates an Entity class named `Sample`.

2. **Create a Request**
   ```bash
   php artisan make:request sample
   ```
   This command creates a Form Request class named `SampleRequest`.

3. **Create a Resource**
   ```bash
   php artisan make:resource sample
   ```
   This command creates a Resource class named `SampleTransformer`.

4. **Create Entity, Request, and Resource Together**
   ```bash
   php artisan make:entitypkg sample
   ```
   This single command generates the following:
   - Entity: `Sample`
   - Request: `SampleRequest`
   - Resource: `SampleTransformer`

   The `make:entitypkg` command combines the creation of the Entity, Request, and Resource into one streamlined operation.

### Example Output

Running the `php artisan make:entitypkg sample` command will produce the following files:

- `app/Entities/Sample.php`
- `app/Requests/SampleRequest.php`
- `app/Transformers/SampleTransformer.php`

# Create Seeder
   ```bash
   php artisan make:seeder FelineDetailSeeder
   ```

# Migration
   ```bash
   php artisan migrate
   ```

   Migrate with Seeder
   ```bash
   php artisan migrate --seed
   ```

   # migrate only a seeder 
   ```bash
   php artisan db:seed --class=DocumentSourceMasterTableSeeder
   ```

# roll back 

```bash
php artisan migrate:rollback --step=1
```

These files can then be customized as needed for your application's specific requirements.
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
