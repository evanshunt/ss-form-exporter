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
  
    php framework/cli-script.php dev/tasks/ExportUserFormToCsv form-id={YOUR_FORM_ID}

### Date Range

Two additional parameters `before` and `after` can be used to narrow your search to specific dates. These both accept date strings according to the rules of PHP's [`strtotime`](https://secure.php.net/manual/en/function.strtotime.php). Eg. `m/d/y`, `d-m-y`, or `YYYY-MM-DD`.

One or both parameters can be used in order to narrow the range.

## Notes

This task may fail if the fields currently defined for the Form do not match the submitted entries.