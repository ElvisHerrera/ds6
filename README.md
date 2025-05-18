# Sistema de Gestión de Empleados DS6

## Introducción y Visión General

### Descripción Breve
Este proyecto es un sistema de gestión de empleados desarrollado para facilitar la administración y el seguimiento de la información de los empleados de una organización. El sistema permite gestionar los datos de los empleados y mantener un registro histórico de los empleados.

### Propósito y Objetivos
- Centralizar la información de empleados
- Facilitar la gestión de datos personales y laborales
- Mantener un registro histórico de empleados
- Controlar el acceso mediante sistema de usuarios

### Audiencia Objetivo
- Administradores de Recursos Humanos
- Personal de administración
- Desarrolladores que mantendrán el sistema
- Usuarios finales que gestionarán la información

## Arquitectura del Sistema

### Diagrama de Arquitectura
```
[Cliente Web] <-> [Servidor Web (XAMPP)] <-> [Base de Datos MySQL]
```

### Componentes Principales
1. **Frontend**
   - Interfaz de usuario en PHP
   - Formularios de gestión
   - Dashboard administrativo
   - Sistema de autenticación

2. **Backend**
   - Procesamiento de datos
   - Gestión de sesiones
   - Manejo de formularios
   - Control de acceso

3. **Base de Datos**
   - Almacenamiento de datos de empleados
   - Gestión de usuarios
   - Registro histórico
   - Catálogos de ubicaciones

### Patrones de Diseño Implementados
- MVC (Modelo-Vista-Controlador)
- Singleton (Conexión a base de datos)
- Factory (Creación de objetos)

### Flujo de Datos
1. Usuario accede al sistema
2. Autenticación mediante login
3. Interacción con formularios
4. Procesamiento de datos
5. Almacenamiento en base de datos
6. Retroalimentación al usuario

## Stack Tecnológico

### Lenguajes de Programación
- PHP 8.2
- SQL
- HTML
- CSS
- JavaScript

### Frameworks y Bibliotecas
- Bootstrap (Frontend)
- jQuery (Interactividad)
- AJAX (Comunicación asíncrona)

### Bases de Datos
- MySQL

### Servicios Externos
- XAMPP (Servidor web local)

## Requisitos del Sistema

### Software Necesario
- XAMPP (Apache + MySQL + PHP)
- Navegador web moderno
- Editor de código (opcional)

### Configuración del Entorno
- PHP 8.2 o superior
- MySQL 10.4.32 o superior
- Apache 2.4 o superior

## Guía de Instalación

### Configuración del Entorno de Desarrollo
1. Instalar XAMPP
2. Clonar el repositorio en la carpeta htdocs
3. Importar la base de datos desde ds6.sql
4. Configurar conexion.php con los datos de la base de datos
5. Acceder al sistema mediante localhost/proyectos/DS6

### Despliegue en Producción
1. Configurar servidor web con PHP y MySQL
2. Transferir archivos al servidor
3. Importar base de datos
4. Configurar conexión a base de datos
5. Ajustar permisos de archivos

### Solución a Problemas Comunes
- Verificar permisos de archivos
- Comprobar configuración de base de datos
- Revisar logs de error de PHP
- Validar conexión a base de datos

## Configuración

### Variables de Entorno
- Configuración de base de datos en conexion.php
- Credenciales de usuario
- Configuración de sesión

### Archivos de Configuración
- conexion.php: Configuración de base de datos
- Configuración de Apache
- Configuración de PHP

## Estructura del Código

### Organización de Directorios
```
DS6/
├── .git/
├── .vs/
├── scripts/
├── styles/
├── *.php
└── ds6.sql
```

### Convenciones de Nomenclatura
- Archivos PHP: camelCase
- Clases: PascalCase
- Variables: camelCase
- Funciones: camelCase
- Constantes: UPPER_CASE

### Patrones Utilizados
- MVC para separación de responsabilidades
- Repository para acceso a datos 