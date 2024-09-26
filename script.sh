#!/bin/bash

dirName=$1

mkdir $(realpath "/var/www/heicconverter/wp-content/uploads/heicconverter/${dirName}")
mkdir $(realpath "/var/www/heicconverter/wp-content/plugins/heicconverter_plugin/temp/${dirName}")
chmod 777 $(realpath "/var/www/heicconverter/wp-content/uploads/heicconverter/${dirName}")
chmod 777 $(realpath "/var/www/heicconverter/wp-content/plugins/heicconverter_plugin/temp/${dirName}")