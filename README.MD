## Instalacion
```
  # activar mod_rewrite para apache
  sudo a2enmod rewrite


```
Para modificar las settings, usar settings.default.json como plantilla,
para crear otro archivo, en el mismo directorio, llamda "settings.json"
y sobrescribir los valores necesarios.

** Si se actulizan las urls, o explota algo de dispatcher, primero provar con borrar las cookies


##Doctrine
```
  
  # mapear entidades desde la DB
  vendor/bin/doctrine orm:convert-mapping --force --from-database annotation ./src/models
  
  # generar getters y setters
  vendor/bin/doctrine orm:generate-entities ./src/models/
  
  # mapear entidades hacia la DB - crear schema
  vendor/bin/doctrine orm:schema-tool:create
  
  # actualizar schema
  vendor/bin/doctrine orm:schema-tool:update
```
