<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>


# Gestión Estudiantil
Este paquete proporciona una forma simple de administrar cursos y estudiantes

# Instalación
Ejecuta el siguiente comando para instalar el paquete:
```bash

go get -u github.com/josetitic/gestionEstudiantil.git

```
Crea en local la base de datos: student_management

```bash
CREATE DATABASE student_management;
```
ejecuta el comando composer:
```bash
composer install

php artisan migrate

```
# Uso
Ejm:
para ejecutar el servidor:
```bash
php artisan serve
```

Se ebe generar un usuario para poder trabajar, ya que los endpoints se autorizan mediante un token de usuario

