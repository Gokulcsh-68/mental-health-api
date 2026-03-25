# Consultations API - Sorting Guidance

The Consultations API now supports dynamic sorting by the `scheduled_at` date and time. This allows for both Ascending (A-Z) and Descending (Z-A) views of the patient/specialist consultations.

## Endpoint

`GET /api/v1/resource/consults`

## Query Parameters

| Parameter | Type | Description |
| :--- | :--- | :--- |
| `sort_order` | `string` | The order to sort by. Possible values: `asc`, `desc`. |
| `from_date` | `string` | Filter consults from this date (inclusive). Format: `YYYY-MM-DD`. |
| `to_date` | `string` | Filter consults to this date (inclusive). Format: `YYYY-MM-DD`. |

## Sorting Options

### 1. Ascending Order (A-Z)
Retrieve consultations starting from the earliest scheduled time to the latest. Useful for seeing upcoming appointments in order.

**Request:**
`GET /api/v1/resource/consults?sort_order=asc`

### 2. Descending Order (Z-A) - Default
Retrieve consultations starting from the latest scheduled time to the earliest. Useful for seeing the most recent or newest bookings first.

**Request:**
`GET /api/v1/resource/consults?sort_order=desc`

## Integration Example (Javascript/Axios)

```javascript
const response = await axios.get('/api/v1/resource/consults', {
    params: {
        sort_order: 'asc',
        limit: 10,
        page: 1
    },
    headers: {
        'Authorization': `Bearer ${token}`
    }
});

console.log('Sorted consults:', response.data.data.consults);
```

## Default Behavior
If the `sort_order` parameter is omitted, the API defaults to `desc` (Z-A) to show the most recent consultations first.
