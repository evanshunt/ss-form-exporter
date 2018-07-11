# Silverstripe form exporter

This module creares a SilverStripe `BuildTask` which will export user form submissions to a `.csv` file.

## Requirements

* Silverstripe 3.x

## Installation 

    composer install evanshunt/ss-form-exporter

## Usage

You need to know the ID of the form you wish to export. It is passed as a url parameter or command line argument.

URL:
    
    http://localhost/dev/tasks/ExportUserFormToCsv?form-id={YOUR_FORM_ID}

CLI:
  
    php framework/cli-script.php dev/build/ExportUserFormToCsv form-id={YOUR_FORM_ID}