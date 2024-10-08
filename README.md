# City of Helsinki - KUVA Drupal project

KUVA, short for Kulttuuri ja vapaa-aika, provides information about cultural and leisure activities in the city of
Helsinki.

## Environments

Env | Branch | Drush alias | URL
--- | ------ | ----------- | ---
development | * | - | http://helfi-kuva.docker.so/
production | main | @main | https://www.hel.fi/fi/kulttuuri-ja-vapaa-aika

## Requirements

You need to have these applications installed to operate on all environments:

- [Docker](https://github.com/druidfi/guidelines/blob/master/docs/docker.md)
- [Stonehenge](https://github.com/druidfi/stonehenge)
- For the new person: Your SSH public key needs to be added to servers

## Create and start the environment

For the first time (new project):

``
$ make new
``

And following times to start the environment:

``
$ make up
``

NOTE: Change these according of the state of your project.

## Login to Drupal container

This will log you inside the app container:

```
$ make shell
```

## Instance specific features

KUVA has no instance specific features.
