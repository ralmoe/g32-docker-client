#!/bin/bash
# MANUAL EXECUTION FOR DEBUGGING

export $(cat .env | xargs) && rails c
printenv | grep OWG
php src/owg.php