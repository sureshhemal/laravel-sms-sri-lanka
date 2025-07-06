# Automatic Packagist Updates Setup

This repository is configured to automatically update Packagist whenever changes are pushed to the main branch or new tags are created.

## Setup Instructions

### 1. Get your Packagist API Token

1. Go to your [Packagist profile](https://packagist.org/profile/)
2. Click on "Show API Token" or generate a new one
3. Copy the token

### 2. Add GitHub Secret

1. Go to your GitHub repository
2. Navigate to Settings → Secrets and variables → Actions
3. Click "New repository secret"
4. Name: `PACKAGIST_TOKEN`
5. Value: Paste your Packagist API token
6. Click "Add secret"

### 3. Configure Packagist Webhooks (Alternative Method)

If you prefer webhooks over GitHub Actions:

1. Go to your package on Packagist: https://packagist.org/packages/sureshhemal/laravel-sms-sri-lanka
2. Click "Settings" on the right side
3. In the "GitHub Hook" section, click "Enable"
4. This will automatically set up a webhook in your GitHub repository

## How It Works

The GitHub Actions workflow will:

1. **On every push to main**:

   - Run tests across PHP 8.1, 8.2, 8.3 and Laravel 10, 11, 12
   - Check the version in composer.json
   - Create a new git tag if version changed
   - Create a GitHub release
   - Update Packagist

2. **On every new tag**:
   - Update Packagist immediately

## Manual Update

If you need to manually update Packagist, you can:

1. Go to your package page on Packagist
2. Click "Force Update" button
3. Or use the API endpoint directly

## Files Created

- `.github/workflows/update-packagist.yml` - Simple Packagist update workflow
- `.github/workflows/ci-cd.yml` - Complete CI/CD pipeline with testing and automatic releases
- `PACKAGIST_SETUP.md` - This documentation file

## Notes

- The workflow automatically creates tags and releases based on the version in composer.json
- Tests run on multiple PHP and Laravel versions to ensure compatibility
- Packagist is updated automatically after successful tests
- Make sure to update the version in composer.json before pushing changes
