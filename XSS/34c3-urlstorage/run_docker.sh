#!/bin/sh
exec docker run -it --rm -p 127.0.0.1:31337:80 --name urlstorage eboda/urlstorage
