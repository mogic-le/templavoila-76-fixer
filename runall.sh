#!/bin/sh
set -ex
rm xml/*.xml || true
./import.sh
php db2xml.php
rm xml/150.*.xml
./fixxml.sh
php xml2db.php
./dumpdb.sh > mysql-fixed-tv_datastructures.sql
