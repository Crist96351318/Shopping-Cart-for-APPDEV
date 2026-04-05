# Notification System Setup Guide

## Overview
The notification system displays order status updates to customers in a bell icon dropdown menu in the header, positioned on the right side next to the Account button.

## Features
- **Real-time Notifications**: Bell icon with badge showing unread notification count
- **Order Status Updates**: Customers receive notifications when order status changes
- **Auto-refresh**: Notifications automatically refresh every 30 seconds
- **Mark as Read**: Users can click on notifications to mark them as read
- **Time Display**: Shows "X minutes ago", "X hours ago", etc.
- **Only for Logged-in Users**: Notifications only appear for authenticated customers

## Database Setup

### 1. Create the Notifications Table
Run the following SQL command to create the notifications table:

```sql
CREATE TABLE IF NOT EXISTS notifications (
    notification_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    order_id INT,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    `read` TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES users(customer_id),
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
    INDEX (customer_id),
    INDEX (created_at)
);
```

Or you can run the provided SQL file:
```bash
mysql -u root -p le_parfum_db < Database/create_notifications_table.sql
```

## Frontend Components

### HTML
The notification bell is added to the header with:
- Bell SVG icon
- Badge displaying unread count
- Dropdown menu showing notifications

Location: `frontend/index.php` (header-actions section)

### CSS Styling
Styles added to `frontend/style.css`:
- `.notification-wrapper` - Container for bell and dropdown
- `.notification-btn` - Bell button styling
- `.notification-badge` - Unread count badge with pulse animation
- `.notification-dropdown` - Dropdown menu styling
- `.notification-item` - Individual notification styling

### JavaScript Functions
Added to `frontend/script.js`:

1. **`loadNotifications()`** - Fetches notifications from backend
2. **`displayNotifications()`** - Renders notifications in dropdown
3. **`markNotificationAsRead()`** - Marks a notification as read
4. **`getTimeAgo()`** - Converts timestamp to relative time
5. **`escapeHtml()`** - Sanitizes HTML to prevent XSS

## Backend Endpoints

### 1. GET /backend/get_notifications.php
Fetches all notifications for a user.

**Parameters:**
- `user_id` (GET) - Customer ID

**Response:**
```json
{
    "success": true,
    "notifications": [
        {
            "id": 1,
            "title": "Order #123 Status Update",
            "message": "Your order has been shipped!",
            "read": false,
            "created_at": "2026-04-05 10:30:00",
            "order_id": 123
        }
    ],
    "count": 1
}
```

### 2. POST /backend/mark_notification_read.php
Marks a notification as read.

**Request Body:**
```json
{
    "notification_id": 1
}
```

**Response:**
```json
{
    "success": true,
    "message": "Notification marked as read"
}
```

## Backend Helper Functions

### `/backend/notification_helper.php`

#### `createNotification($conn, $customer_id, $title, $message, $order_id = null)`
Creates a notification for a user.

**Example:**
```php
createNotification($conn, 5, "Order Confirmed", "Your order #123 has been confirmed.", 123);
```

#### `notifyOrderStatusUpdate($conn, $order_id, $new_status, $message = null)`
Creates a notification when order status changes. Automatically generates appropriate messages.

**Supported Statuses:**
- `Pending` - Order received
- `Processing` - Being prepared
- `Shipped` - On the way (includes tracking info message)
- `Delivered` - Successfully delivered
- `Cancelled` - Order cancelled

**Example:**
```php
require_once('notification_helper.php');
notifyOrderStatusUpdate($conn, 123, 'Shipped');
```

## Integration Points

### Notify When Order is Created
In `checkout.php`, after successful order creation:
```php
require_once('notification_helper.php');
createNotification($conn, $customer_id, "Order Received", "Thank you for your order! Order #" . $order_id, $order_id);
```

### Notify When Order Status Changes
In `update_order_status.php` (already integrated):
```php
notifyOrderStatusUpdate($conn, $order_id, $new_status);
```

## How Customers See Notifications

1. **Bell Icon**: Visible in header next to Account button (only when logged in)
2. **Badge**: Red badge with unread count appears when there are new notifications
3. **Dropdown Menu**: Click bell to see notification list
4. **Time Stamps**: Each notification shows "15 minutes ago", "2 hours ago", etc.
5. **Mark as Read**: Click a notification to mark it as read
6. **Auto-refresh**: Notifications update every 30 seconds automatically

## Testing

### Manual Testing Steps

1. **Create a test order:**
   - Log in as customer
   - Place an order

2. **Update order status:**
   - Log in as admin
   - Go to order management
   - Change order status (e.g., from "Pending" to "Shipped")

3. **Check notifications:**
   - Switch back to customer account
   - Refresh page or wait 30 seconds
   - Bell icon should show badge with unread count
   - Click bell to see order update notification

### Debug Queries

Check notifications in database:
```sql
SELECT * FROM notifications WHERE customer_id = 5;
```

Check order status:
```sql
SELECT o.order_id, o.status, o.customer_id FROM orders o WHERE o.order_id = 123;
```

## Customization

### Change Refresh Rate
In `frontend/script.js`, change the interval from `30000` (30 seconds) to desired milliseconds:
```javascript
setInterval(loadNotifications, 60000); // 60 seconds
```

### Change Notification Messages
Edit the `notifyOrderStatusUpdate()` function in `/backend/notification_helper.php` to customize messages for each status.

### Change Styling
Modify the CSS classes in `frontend/style.css` for colors, animations, sizes, etc.

## Troubleshooting

### No notifications showing
1. Verify notifications table exists: `SHOW TABLES;`
2. Check if user is logged in (notifications only show for authenticated users)
3. Check browser console for JavaScript errors
4. Verify `get_notifications.php` returns data correctly

### Notifications not updating
1. Check if JavaScript interval is running
2. Verify API endpoint is returning correct data
3. Check network requests in browser DevTools
4. Look for errors in browser console

### Badge not showing
1. Verify unread notifications exist in database
2. Check if `notification-badge` element exists in DOM
3. Verify CSS is properly loaded

## Files Modified/Created

- ✅ `frontend/index.php` - Added notification bell HTML
- ✅ `frontend/style.css` - Added notification styling
- ✅ `frontend/script.js` - Added notification JavaScript logic
- ✅ `backend/get_notifications.php` - Fetch notifications endpoint
- ✅ `backend/mark_notification_read.php` - Mark as read endpoint
- ✅ `backend/notification_helper.php` - Notification creation functions
- ✅ `backend/update_order_status.php` - Updated to create notifications
- ✅ `Database/create_notifications_table.sql` - Database table schema
