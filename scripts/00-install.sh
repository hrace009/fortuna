#!/usr/bin/env bash

# Install composer dependecies
composer install --no-interaction --no-dev --prefer-dist

# Install packages
yarn

# Compile assets
yarn run dev