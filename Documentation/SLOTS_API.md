# Get Available Slots API Documentation

This endpoint retrieves the available (and booked/blocked) time slots for a specific specialist on a given date or across a date range.

## Endpoint

**URL**: `/api/v1/specialists/schedule/slots`  
**Method**: `GET`  
**Access**: Private (Requires standard Bearer Token and API Key)

---

## Query Parameters

| Parameter | Type | Required | Description |

| `date` | String | No* | Specific date in `YYYY-MM-DD` or `DD-MM-YYYY` format. |
| `time` | String | No | Point-in-time filter (e.g., `14:30`, `02:30 PM`). Returns the specific slot starting at this time. |

> [!NOTE]
> You must provide either `date`.

---

## Response Formats

### 1. Single Date Request
If a single `date` is provided:

**Example Response**:
```json
{
  "success": true,
  "message": "Available slots fetched successfully",
  "data": {
    "specialist_id": 4,
    "date": "2026-03-18",
    "slots": [
      {
        "startTime": "14:00",
        "endTime": "14:30",
        "available": true,
        "reason": null
      },
      {
        "startTime": "14:30",
        "endTime": "15:00",
        "available": false,
        "reason": "booked"
      }
    ]
  }
}
```

### 2. Date Range Request
If `startDate` and `endDate` are provided:

**Example Response**:
```json
{
  "success": true,
  "message": "Available slots fetched successfully",
  "data": {
    "specialist_id": 4,
    "results": {
      "2026-03-18": [ /* slots array */ ],
      "2026-03-19": [ /* slots array */ ],
      "2026-03-20": [ /* slots array */ ]
    }
  }
}
```

---

## Slot Statuses (`reason`)

When `available` is `false`, the `reason` field explains why:
- `past`: The slot time has already passed for the current day.
- `break`: The specialist has a scheduled break during this time.
- `blocked`: The specialist has marked this time as unavailable.
- `booked`: Another patient has already booked this slot.
