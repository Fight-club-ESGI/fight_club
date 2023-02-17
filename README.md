# FIGHT CLUB - PROJECT
___
## DESCRIPTION

## SUMMARY

## INSTALLATION

### Install the project
```
$ docker-compose up --build
```

```
$ docker-compose exec front npm run install
```

```
$ docker-compose exec php bin/console lexik:jwt:generate-keypair
```

```
$ docker-compose exec php bin/console doctrine:migrations:migrate
```

### Run the project
```
$ docker-compose exec front npm run dev
```

### PACKAGE

| Package                                                                               | Version |
|---------------------------------------------------------------------------------------|---------|
| [API Platform](https://github.com/api-platform/api-platform)                          | ^3.0.9  |
| [LexikJWTAuthenticationBundle](https://github.com/lexik/LexikJWTAuthenticationBundle) | ^2.18   |
| [JWTRefreshTokenBundle](https://github.com/markitosgv/JWTRefreshTokenBundle)          | ^1.1    |
| VichUploader                                                                          ||
| stof doctrine extensions                                                              |
## COMMAND

```
$ docker-compose exec front npm run dev
```
### CLI

### MAKEFILE

## CONTRIBUTOR
