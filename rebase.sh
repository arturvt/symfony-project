#!/bin/sh

echo " -=-=- Cleaning database -=-=- "

app/console cache:clear

echo " -=-=- Dropping database -=-=- "
app/console doctrine:database:drop --force -n

echo " -=-=- Creating database -=-=- "

app/console doctrine:database:create -n

echo " -=-=- Updating database -=-=- "
app/console doctrine:schema:update --force -n

echo " -=-=- Loading fixtures -=-=- "

app/console doctrine:fixtures:load -n


