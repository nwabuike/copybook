# Authentication & Authorization System - Complete Guide

## Overview
The MagicBook system now includes a comprehensive authentication and authorization system with role-based access control (RBAC), activity logging, and user management. Every action is tracked and attributed to the user who performed it.

---

## Features

### 🔐 Authentication
- **Secure Login**: Password hashing with bcrypt
- **Session Management**: PHP sessions with automatic timeout
- **Remember Me**: Optional persistent login
- **Logout**: Clean session termination

### 👥 User Roles
1. **Admin** - Full system access
   - View/Create/Edit/Delete all records
   - Manage users
   - View analytics reports
   - View activity logs
   - Export data

2. **Subadmin** - Limited management access
   - View/Create/Edit orders
   - View/Edit agents
   - View/Update stock
   - View sales notifications
   - Cannot delete records
   - Cannot view analytics
   - Cannot manage users

3. **Agent** - Delivery agent access
   - View assigned orders
   - Update order status
   - View stock levels

### 📝 Activity Logging
Every action is automatically logged with:
- User who performed the action
- Action type (login, logout, create, update, delete)
- Entity affected (order, user, agent, stock)
- Timestamp
- IP address
- User agent (browser info)
- Old and new values (for updates)

---

## Installation

### 1. Database Setup
```bash
# Execute the SQL script to create tables
Get-Content sql\create_auth_system.sql | C:\laragon\bin\mysql\mysql-8.4.3-winx64\bin\mysql.exe -u root copybook
```

This creates:
- `users` table - User accounts with roles
- `activity_logs` table - Complete audit trail
- `user_sessions` table - Session management
- Adds `updated_by` columns to existing tables

### 2. Default Accounts
The system includes 3 test accounts:

| Username | Password | Role | Description |
|----------|----------|------|-------------|
| `admin` | `admin123` | Admin | Full system access |
| `subadmin` | `subadmin123` | Subadmin | Limited management |
| `agent001` | `agent123` | Agent | Delivery agent |

**⚠️ IMPORTANT**: Change these passwords immediately after first login!

---

## Usage

### Login Process
1. Navigate to: `http://localhost/magicbook/login.php`
2. Enter username or email
3. Enter password
4. Optional: Check "Remember me"
5. Click "Login"

### First Time Setup
1. Login as `admin` / `admin123`
2. Go to **User Management**
3. Change admin password
4. Create real user accounts
5. Disable or delete test accounts

---

## User Management

### Creating Users
**Access**: Admin only  
**Page**: `user_management.php`

1. Click "Add New User"
2. Fill in required fields:
   - Username (unique)
   - Full Name
   - Email (unique)
   - Role (admin/subadmin/agent)
   - Password (min 6 characters)
   - Status (active/inactive)
3. Click "Save User"

### Editing Users
1. Click "Edit" button on user row
2. Modify fields (password optional)
3. Click "Save User"

### Deleting Users
- Only admins can delete users
- Cannot delete your own account
- Deletion is permanent and logged

---

## Role Permissions Matrix

| Permission | Admin | Subadmin | Agent |
|------------|-------|----------|-------|
| **Orders** |
| View orders | ✅ | ✅ | ✅ (own only) |
| Create order | ✅ | ✅ | ❌ |
| Edit order | ✅ | ✅ | ❌ |
| Delete order | ✅ | ❌ | ❌ |
| Update status | ✅ | ✅ | ✅ |
| **Agents** |
| View agents | ✅ | ✅ | ❌ |
| Create agent | ✅ | ❌ | ❌ |
| Edit agent | ✅ | ✅ | ❌ |
| Delete agent | ✅ | ❌ | ❌ |
| **Stock** |
| View stock | ✅ | ✅ | ✅ |
| Update stock | ✅ | ✅ | ❌ |
| Delete stock movement | ✅ | ❌ | ❌ |
| **Analytics** |
| View analytics | ✅ | ❌ | ❌ |
| Export data | ✅ | ✅ | ❌ |
| **Users** |
| View users | ✅ | ❌ | ❌ |
| Create user | ✅ | ❌ | ❌ |
| Edit user | ✅ | ❌ | ❌ |
| Delete user | ✅ | ❌ | ❌ |
| **Logs** |
| View activity logs | ✅ | ❌ | ❌ |
| View all activities | ✅ | ❌ | ❌ |

---

## Activity Logs

### Viewing Logs
**Access**: Admin only  
**Page**: `activity_logs.php`

### Filters Available
- **User**: Filter by specific user
- **Action**: login, logout, create, update, update_status, delete
- **Entity Type**: order, user, agent, stock
- **Date Range**: Custom start and end dates

### Log Information
Each log entry shows:
- Timestamp (when action occurred)
- User (with avatar and username)
- Action type (color-coded badge)
- Entity affected (with ID)
- Description (detailed explanation)
- IP address

### Common Use Cases

**1. Track Order Changes**
```
Filter: Entity Type = "order", Action = "update_status"
```

**2. Monitor User Activities**
```
Filter: User = specific user, Date Range = last 30 days
```

**3. Security Audit**
```
Filter: Action = "login" or "delete", Date Range = all time
```

**4. Data Recovery**
```
Filter: Action = "delete", Entity Type = "order"
View old_values JSON to recover deleted data
```

---

## API Security

### Authentication Required
All API endpoints now require authentication:
```php
require_once '../php/auth.php';
requireLogin();
```

### Permission Checks
Sensitive operations check permissions:
```php
if (!canPerform('delete_order')) {
    echo json_encode(['success' => false, 'message' => 'Permission denied']);
    return;
}
```

### Activity Logging in APIs
Every API operation logs activity:
```php
logActivity(
    $_SESSION['user_id'],
    'update_status',
    'order',
    $orderId,
    "Updated order status from pending to confirmed"
);
```

---

## Page Protection

### Adding Authentication to New Pages
```php
<?php
require_once 'php/auth.php';
requireLogin(); // All logged-in users

// OR

requireRole('admin'); // Admin only

// OR

requireRole(['admin', 'subadmin']); // Multiple roles
?>
```

### Checking Permissions in Code
```php
<?php if (canPerform('delete_order')): ?>
    <button class="delete-btn">Delete</button>
<?php endif; ?>
```

### Getting Current User
```php
$currentUser = getCurrentUser();
echo $currentUser['full_name'];
echo $currentUser['role'];
echo $currentUser['email'];
```

---

## Navigation Updates

### Header with User Info
All admin pages now show:
- User avatar (first letter of name)
- Full name
- Role badge
- Logout button

### Role-Based Menu
- **Everyone**: Home, Orders, Agents, Alerts
- **Admin Only**: Analytics, Users, Logs
- **Subadmin**: Limited alerts access

---

## Security Best Practices

### Production Deployment
1. **Change Default Passwords**
   ```
   Login as admin → User Management → Edit admin → Change password
   ```

2. **Use Strong Passwords**
   - Minimum 12 characters
   - Mix of letters, numbers, symbols
   - Unique for each user

3. **Enable HTTPS**
   ```
   All login and session data should use SSL/TLS
   ```

4. **Regular Audits**
   - Review activity logs weekly
   - Check for suspicious login attempts
   - Monitor delete operations

5. **Disable Test Accounts**
   ```
   Set status to "inactive" or delete test users
   ```

6. **Session Security**
   - Sessions expire after inactivity
   - Logout when done
   - Use different browser for admin

---

## Troubleshooting

### Issue: "Not logged in" error
**Cause**: Session expired or cookies disabled  
**Solution**:
1. Clear browser cache
2. Enable cookies
3. Login again

### Issue: "Permission denied"
**Cause**: Insufficient role permissions  
**Solution**:
1. Check user role in User Management
2. Contact admin to upgrade role
3. Use appropriate account for task

### Issue: Activity logs not showing
**Cause**: Not logged in as admin  
**Solution**:
1. Login as admin user
2. Only admins can view logs

### Issue: Cannot delete orders
**Cause**: Subadmin role restriction  
**Solution**:
1. Subadmins cannot delete
2. Contact admin to delete
3. Or change status to "cancelled"

### Issue: Login fails with correct password
**Cause**: Account inactive or database issue  
**Solution**:
1. Check user status in database
2. Verify database connection
3. Check browser console for errors

---

## Database Schema

### users Table
```sql
id INT PRIMARY KEY AUTO_INCREMENT
username VARCHAR(50) UNIQUE NOT NULL
email VARCHAR(100) UNIQUE NOT NULL
password VARCHAR(255) NOT NULL (bcrypt hashed)
full_name VARCHAR(100) NOT NULL
role ENUM('admin', 'subadmin', 'agent')
status ENUM('active', 'inactive')
last_login DATETIME
created_at TIMESTAMP
updated_at TIMESTAMP
created_by INT (FK to users.id)
```

### activity_logs Table
```sql
id INT PRIMARY KEY AUTO_INCREMENT
user_id INT NOT NULL (FK to users.id)
action VARCHAR(50) NOT NULL
entity_type VARCHAR(50) NOT NULL
entity_id VARCHAR(50)
description TEXT NOT NULL
ip_address VARCHAR(45)
user_agent TEXT
old_values JSON
new_values JSON
created_at TIMESTAMP
```

---

## API Endpoints

### Authentication
```
POST /api/auth.php?action=login
Body: {username, password}
Response: {success, message, user}

POST /api/auth.php?action=logout
Response: {success, message}

GET /api/auth.php?action=check
Response: {success, logged_in, user}
```

### User Management (Admin Only)
```
GET /api/users.php?action=list
Response: {success, data: [users]}

GET /api/users.php?action=single&id=1
Response: {success, data: {user}}

POST /api/users.php?action=create
Body: {username, email, password, full_name, role, status}
Response: {success, message, user_id}

POST /api/users.php?action=update
Body: {user_id, ...fields}
Response: {success, message}

POST /api/users.php?action=delete
Body: {user_id}
Response: {success, message}
```

### Activity Logs (Admin Only)
```
GET /api/activity_logs.php?user_id=1&action=login&start_date=2025-01-01&end_date=2025-12-31&page=1&per_page=50
Response: {success, data: [logs], pagination}
```

---

## Code Examples

### Custom Permission Check
```php
function canPerform($action) {
    $permissions = [
        'admin' => ['all'],
        'subadmin' => ['view_orders', 'edit_orders'],
        'agent' => ['view_orders']
    ];
    
    $role = $_SESSION['user_role'];
    return in_array($action, $permissions[$role]) || 
           in_array('all', $permissions[$role]);
}
```

### Manual Activity Logging
```php
logActivity(
    $_SESSION['user_id'],
    'custom_action',
    'custom_entity',
    $entityId,
    'Description of what happened',
    ['old' => 'data'], // optional
    ['new' => 'data']  // optional
);
```

### Get User's Recent Activities
```php
$activities = getUserActivities($_SESSION['user_id'], 20);
foreach ($activities as $activity) {
    echo $activity['description'];
}
```

---

## Future Enhancements

### Planned Features
- 📧 Email notifications for security events
- 🔒 Two-factor authentication (2FA)
- 🔑 Password reset via email
- 📱 Mobile app authentication
- 🔄 Auto-logout on inactivity
- 🌐 OAuth integration (Google, Facebook)
- 📊 User activity dashboard
- 🚨 Failed login attempt blocking
- 📝 Audit report generation
- 🔐 API key authentication

---

## Support & Maintenance

### Regular Tasks
- [ ] Weekly: Review activity logs
- [ ] Monthly: Audit user accounts
- [ ] Quarterly: Change admin passwords
- [ ] Yearly: Review role permissions

### Backup Recommendations
- Backup `users` table daily
- Backup `activity_logs` table weekly
- Keep logs for minimum 1 year
- Archive old logs to separate database

---

## Contact & Updates

**Last Updated**: November 21, 2025  
**Version**: 1.0  
**Status**: Production Ready ✅

### Change Log
- ✅ User authentication system
- ✅ Role-based access control
- ✅ Activity logging system
- ✅ User management interface
- ✅ Protected all admin pages
- ✅ API security implementation
- ✅ Activity logs viewer
