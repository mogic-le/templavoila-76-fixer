#!/bin/sh
echo "TRUNCATE tx_templavoila_datastructure;"
mysqldump -uFIXMEUSER -pFIXMEPASS --no-create-info FIXMEDBNAME tx_templavoila_datastructure
