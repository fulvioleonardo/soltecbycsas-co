## PROCEDIMIENTO DE ACTUALIZACION

A tomar en cuenta:
* El facturador se encontrará desplegado mediante al menos 5 contenedores de Docker
* Para actualizar se requiere ingresar solo a uno de ellos

### pasos

1. ejecutar `docker ps`
2. Aparecera un listado con los contenedores activos, debemos identificar el contenedor mediante el **COMMAND** con valor `php-fpm7.2` o similar
3. copiar el primer valor de la linea equivalente a **CONTAINER ID**
4. ejecutar `docker exec -ti codigodelcontenedor bash`
5. una vez ingresado al contenedor, ejecutar `git pull origin master`
6. ingresar las credenciales solicitadas (correo y contraseña)
7. ejecutar `php artisan migrate && php artisan tenancy:migrate`
8. ejecutar `php artisan config:cache && php artisan cache:clear && php artisan optimize:clear`
