#!/usr/bin/env bash

if [[ $1 == "--install" ]]; then
    echo "Installing..."
    composer install && npm install
elif [[ $1 == "--prod" ]] || [[ $1 == "--production" ]]; then
    echo "Production build..."
    npm run production
else
    echo "Watching..."
    npm run watch
fi
