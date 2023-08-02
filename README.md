<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Užduoties backend'as

### Kaip pasileisti su Sail ir Docker


Pasiklonavus repozitoriją reikia sudiegti composer depenencies:

```
composer install
```

Reikalingas `.env` failas, kuris gaunamas iš `.env.example`

```
cp .env.example .env
```

Kartu bus sudiegiamas `Sail` paketas kuris ir bus naudojamas tolimesniam paleidimui.

```
sail up --build -d
```

Paleidžiamos migracijos:

```
sail artisan migrate
```

Produktų importavimas:

```
sail artisan app:import-products
```

Produktų likučių importavimo testavimas
```
sail artisan schedule:test
```
