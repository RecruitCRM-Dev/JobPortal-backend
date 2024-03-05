
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

