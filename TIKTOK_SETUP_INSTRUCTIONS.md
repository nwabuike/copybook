# TikTok Landing Page Setup Instructions

## Overview
The file `index-tiktok.php` has been created as a dedicated landing page for TikTok ads with TikTok Pixel tracking.

## Setup Steps

### 1. Get Your TikTok Pixel ID
1. Go to [TikTok Ads Manager](https://ads.tiktok.com)
2. Navigate to **Assets** → **Events**
3. Click **Web Events** → **Manage**
4. If you don't have a pixel, create one:
   - Click **Create Pixel**
   - Choose **TikTok Pixel**
   - Name it (e.g., "Magic Copy Book Pixel")
   - Click **Next** → **Manually Install Pixel Code**
5. Copy your Pixel ID (it looks like: `CXXXXXXXXXXXXXXXX`)

### 2. Add Your TikTok Pixel ID
1. Open `index-tiktok.php` in your editor
2. Find line ~1693 (search for: `YOUR_TIKTOK_PIXEL_ID`)
3. Replace `YOUR_TIKTOK_PIXEL_ID` with your actual Pixel ID

**Before:**
```javascript
ttq.load('YOUR_TIKTOK_PIXEL_ID');
```

**After:**
```javascript
ttq.load('C123ABCDEFGH45678');  // Your actual Pixel ID
```

### 3. Create Your TikTok Ad
1. Go to TikTok Ads Manager
2. Create a new campaign
3. In ad creation, set the Landing Page URL to:
   ```
   https://yourdomain.com/index-tiktok.php
   ```

### 4. Verify Pixel Installation
1. Install [TikTok Pixel Helper](https://chrome.google.com/webstore) browser extension
2. Visit your `index-tiktok.php` page
3. Click the extension icon - it should show:
   - ✅ PageView event detected
   - ✅ Your Pixel ID
4. Test an order to verify CompletePayment event fires

## Traffic Source Tracking

### How It Works
- **Facebook orders**: Come through `index.php` → Tagged as `source=facebook`
- **TikTok orders**: Come through `index-tiktok.php` → Tagged as `source=tiktok`
- All orders save to the same database with source tracking

### View Performance
Go to your admin dashboard to see:
- Total orders per source (Facebook vs TikTok)
- Revenue per source
- Conversion rates
- Traffic source analytics

## Testing

### Test Facebook Page
URL: `https://yourdomain.com/index.php`
- Should have Facebook Pixel only
- Orders tagged as `facebook`

### Test TikTok Page
URL: `https://yourdomain.com/index-tiktok.php`
- Should have both Facebook & TikTok Pixels
- Orders tagged as `tiktok`

## Troubleshooting

### Pixel Not Firing
1. Check browser console for JavaScript errors
2. Verify Pixel ID is correct (no quotes or spaces)
3. Clear browser cache and test again
4. Use TikTok Pixel Helper to debug

### Orders Not Tracking Source
1. Check database orders table has `source` column
2. Run migration: `php add_source_column.php`
3. Check form has hidden input: `<input type="hidden" name="source" value="tiktok">`

## Important Notes
- ✅ Both pixels can coexist without conflicts
- ✅ Facebook pixel remains on both pages for retargeting
- ✅ Only TikTok ads should link to `index-tiktok.php`
- ✅ Keep existing Facebook ads pointing to `index.php`
- ⚠️ Don't delete `add_source_column.php` until migration is confirmed successful

## Need Help?
Check the admin dashboard analytics to verify orders are being tracked with correct sources.
