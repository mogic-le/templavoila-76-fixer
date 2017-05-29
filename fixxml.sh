#!/bin/sh
set -e

for file in xml/*.orig.xml; do
    newname=`echo $file | sed s/orig/fixed/`
    echo $file - $newname
    php fix-tvs.php $file > $newname
done
