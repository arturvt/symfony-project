#!/bin/sh

app/console cache:clear
app/console doctrine:database:drop --force -n
app/console doctrine:database:create -n
app/console doctrine:schema:update --force -n
#app/console doctrine:fixtures:load -n
