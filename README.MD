## Instalacion
```
  # activar mod_rewrite para apache
  sudo a2enmod rewrite


  #Doctrine
  #mapear entidades desde la DB
  vendor/bin/doctrine orm:convert-mapping --force --from-database annotation ./src/models
  
  # generar getters y setters
  vendor/bin/doctrine orm:generate-entiites ./src/models/
 
```
Para modificar las settings, usar settings.default.json como plantilla,
para crear otro archivo, en el mismo directorio, llamda "settings.json"
y sobrescribir los valores necesarios.