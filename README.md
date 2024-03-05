
<h1 align="center" id="title">Job Portal</h1>

<p id="description">The job portal project aims to establish a dynamic platform that facilitates seamless connections between employers and candidates. The primary goal is to streamline the hiring process making it efficient and effective for both parties involved.</p>

<h2>🛠️ Installation Steps:</h2>

<p>1. First clone this repository install the dependencies and setup your .env file.</p>

```
git clone https://github.com/RecruitCRM-Dev/JobPortal-backend.git

composer install

npm install

cp .env.example .env
```


<p>2. Generate Key</p>

```
php artisan key:generate
```

<p>3. Then create the necessary database.</p>

```
php artisan db create database job-portal
```

<p>4. And run the initial migrations and seeders.</p>


```
php artisan migrate --seed
```

## API Endpoints

## Employer

### 1. Register:
- Endpoint: `employer/register` (POST)

### 2. Login:
- Endpoint: `employer/login` (POST)

### 3. Logout:
- Endpoint: `employer/logout` (POST)

## Profile

### 1. Get Profile Data:
- Endpoint: `employer/profile/:id` (GET)

### 2. Profile Update:
- Endpoint: `employer/profile/:id` (POST)

## Post Jobs

### 1. Get all the users of employer -> employerId with job -> JobId:
- Endpoint: `employer/:employerId/jobs/:jobId` (GET)

### 2. Posted all jobs:
- Endpoint: `employer/:employerId/jobs` (GET)

### 3. Post a job:
- Endpoint: `employer/:employerId/jobs` (POST)

### 4. Update Job Status:
- Endpoint: `employer/:employerId/jobs/:jobId` (PUT)

## User

### 1. Register:
- Endpoint: `user/register` (POST)

### 2. Login:
- Endpoint: `user/login` (POST)

### 3. Logout:
- Endpoint: `user/logout` (POST)

## Profile

### 1. Get Profile Data:
- Endpoint: `user/profile/:id` (GET)

### 2. Profile Update:
- Endpoint: `user/profile/:id` (POST)

## Job Application

### 1. Apply for a Job:
- Endpoint: `user/:id/jobs` (POST)

### 2. List all the jobs for a user (applied jobs):
- Endpoint: `user/:userId/jobs` (GET)

### 3. Check if the user has applied for a job or not:
- Endpoint: `user/:userId/jobs/:jobId` (GET)

## General Routes

### 1. Get all jobs:
- Endpoint: `/jobs` (GET)

### 2. Job detail:
- Endpoint: `/jobs/:id` (GET)

### 3. Latest jobs:
- Endpoint: `/jobs/latest` (GET)

### 4. Send forgot password link:
- Endpoint: `/forgot-password` (POST)

### 5. Reset password:
- Endpoint: `/reset-password/:token` (POST)

### 6. Send verification mail:
- Endpoint: `/email/resend` (POST)

### 7. Verify email address:
- Endpoint: `/email/verify/:userId?exp&hash` (GET)
