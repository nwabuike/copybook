# User Account Activation/Deactivation - Admin Only Feature

## Overview
Implemented secure user account activation and deactivation functionality that is **exclusively accessible to administrators**. This feature allows admins to control user access to the system without permanently deleting accounts.

## Changes Made

### 1. API Endpoint (`api/users.php`)
Added new `toggle_status` action that:
- Toggles user status between 'active' and 'inactive'
- Prevents admins from changing their own status (safety measure)
- Logs all status changes with full audit trail
- Returns success message with new status

**Security:**
- Protected by `requireAdmin()` at API level (line 11)
- Only administrators can call this endpoint
- All actions are logged in activity_logs table

### 2. User Management UI (`user_management.php`)
Added activate/deactivate buttons to user table:
- **Active users:** Yellow "Deactivate" button with ban icon
- **Inactive users:** Green "Activate" button with check-circle icon
- Current user cannot change their own status (shows "Current User" label instead)
- Confirmation dialogs before status changes
- Real-time status updates after changes

**Button Styling:**
- `.btn-warning` class added for deactivate button (yellow background)
- Color-coded for easy identification
- Hover effects for better UX

### 3. JavaScript Function (`user_management.php`)
Added `toggleUserStatus()` function:
- Confirms action with descriptive message
- Sends POST request to API
- Refreshes user list on success
- Shows success/error alerts

## User Flow

### Deactivating a User:
1. Admin clicks yellow "Deactivate" button on active user
2. Confirmation: "Are you sure you want to deactivate user [username]? They will not be able to log in."
3. Upon confirmation, user status changes to 'inactive'
4. User cannot log in until reactivated
5. Action logged in activity_logs

### Activating a User:
1. Admin clicks green "Activate" button on inactive user
2. Confirmation: "Are you sure you want to activate user [username]? They will be able to log in."
3. Upon confirmation, user status changes to 'active'
4. User can now log in normally
5. Action logged in activity_logs

## Security Features

### Admin-Only Access:
✅ API protected with `requireAdmin()` at entry point  
✅ UI page protected with `requireAdmin()` in PHP  
✅ Subadmins and agents cannot access this functionality  
✅ Non-admin API calls return 403 Unauthorized

### Safety Measures:
✅ Admins cannot change their own account status  
✅ Confirmation dialogs prevent accidental changes  
✅ All actions are logged with user ID, timestamp, old/new values  
✅ Username validation prevents SQL injection

### Audit Trail:
All activation/deactivation actions are logged in `activity_logs` table with:
- User who performed the action (admin)
- Action type: 'update_status'
- Target user ID
- Description: "Activated/Deactivated user: [username] ([role])"
- Old values: previous status
- New values: new status
- Timestamp and IP address

## Database Impact

### No Schema Changes Required
Uses existing `users.status` column (already in schema):
- Values: 'active' or 'inactive'
- Default: 'active'

### Activity Logging
Status changes logged to `activity_logs` table:
```sql
INSERT INTO activity_logs 
(user_id, action, entity_type, entity_id, description, old_values, new_values, ip_address, user_agent)
VALUES (?, 'update_status', 'user', ?, ?, ?, ?, ?, ?)
```

## Permissions Matrix

| Role     | View Users | Edit Users | Activate/Deactivate | Delete Users |
|----------|------------|------------|---------------------|--------------|
| Admin    | ✅         | ✅         | ✅                  | ✅           |
| Subadmin | ❌         | ❌         | ❌                  | ❌           |
| Agent    | ❌         | ❌         | ❌                  | ❌           |

## Testing Checklist

### As Admin:
- [x] Can view all users in user management page
- [x] Can see activate/deactivate buttons for all users except self
- [x] Can deactivate an active user
- [x] Can activate an inactive user
- [x] Cannot change own status (button not shown)
- [x] Status changes are logged in activity logs
- [x] Deactivated users cannot log in

### As Non-Admin:
- [x] Cannot access user_management.php (redirected)
- [x] Cannot call toggle_status API endpoint (403 error)
- [x] No way to change any user status

### Login Behavior:
- [x] Active users can log in normally
- [x] Inactive users see: "Your account has been deactivated"
- [x] Inactive users cannot access any pages

## API Documentation

### Toggle User Status
**Endpoint:** `POST api/users.php?action=toggle_status`

**Auth Required:** Yes (Admin only)

**Request Body:**
```json
{
  "user_id": 5
}
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "User activated successfully",
  "new_status": "active"
}
```

**Error Responses:**

**403 Forbidden** (Non-admin):
```json
{
  "success": false,
  "message": "Only administrators can access this resource"
}
```

**400 Bad Request** (Own account):
```json
{
  "success": false,
  "message": "You cannot change your own account status"
}
```

**404 Not Found:**
```json
{
  "success": false,
  "message": "User not found"
}
```

## Files Modified

1. **api/users.php**
   - Added `toggle_status` case in POST action switch
   - Added `toggleUserStatus()` function (lines 315-370)

2. **user_management.php**
   - Added `.btn-warning` CSS class (lines 285-293)
   - Updated user table action buttons (lines 755-768)
   - Added `toggleUserStatus()` JavaScript function (lines 843-865)

## Rollback Instructions

If you need to remove this feature:

1. **In api/users.php**, remove:
   ```php
   case 'toggle_status':
       toggleUserStatus();
       break;
   ```
   And delete the `toggleUserStatus()` function

2. **In user_management.php**, revert the action buttons section to:
   ```html
   <button class="btn btn-sm" onclick="editUser(${user.id})">
       <i class="fas fa-edit"></i> Edit
   </button>
   ${user.id !== <?= $currentUser['id'] ?> ? `
       <button class="btn btn-danger btn-sm" onclick="deleteUser(${user.id}, '${user.username}')">
           <i class="fas fa-trash"></i> Delete
       </button>
   ` : ''}
   ```

3. Remove the `toggleUserStatus()` JavaScript function

## Support

### Common Issues:

**Issue:** Non-admin users see user management in sidebar  
**Solution:** Check that sidebar links are wrapped with `<?php if (isAdmin()): ?>`

**Issue:** Deactivated user still logging in  
**Solution:** Check `php/auth.php` line 138 validates `status === 'active'`

**Issue:** Status changes not logging  
**Solution:** Verify `activity_logs` table exists and `logActivity()` function works

## Future Enhancements (Not Implemented)

- [ ] Bulk activate/deactivate multiple users
- [ ] Schedule automatic deactivation (temporary access)
- [ ] Email notifications when account is deactivated
- [ ] Reason field for deactivation
- [ ] Reactivation approval workflow
- [ ] User self-service account reactivation request

---

**Implementation Date:** November 23, 2025  
**Security Level:** Admin Only  
**Tested:** ✅ All test cases passed
