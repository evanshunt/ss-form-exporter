<?php
class ExportUserFormToCsv extends BuildTask {
    protected $title = "Export User Form data to CSV";
    protected $description = "Generates a CSV from form submissions for a given 'form-id'";

    public function run($request) {

        if(isset($_GET['form-id'])) {
            $submitted = SubmittedForm::get()->filter(['ParentID' => $_GET['form-id']]);

            if (isset($_GET['before'])) {
                $timestampBefore = strtotime($_GET['before']);
                $dateTimeBefore = date("Y-m-d H:i:s", $timestampBefore);
                $submitted = $submitted->filter(['Created:LessThan' => $dateTimeBefore]);
            }

            if (isset($_GET['after'])) {
                $timestampAfter = strtotime($_GET['after']);
                $dateTimeAfter = date("Y-m-d H:i:s", $timestampAfter);
                $submitted = $submitted->filter(['Created:GreaterThan' => $dateTimeAfter]);
            }

            $fields = EditableFormField::get()->filter(['ParentID' => $_GET['form-id']]);
            $gridField = new GridField('Submission', 'Submissions', $submitted);
            $exportButton = new GridFieldExportButton();
     
            $exportColumns = [
                'ID' => 'Submission ID',
                'Created' => 'Created'
            ];
            
            foreach ($fields as $field) {
                $exportColumns[$field->Name] = $field->Title;
            }

            $exportButton->setExportColumns($exportColumns);
            $exportData = $exportButton->generateExportFileData($gridField);

            if (!is_dir('../csvs/')) {
              // dir doesn't exist, make it
              mkdir('../csvs/');
            }

            $filename = 'form-id-' . $_GET['form-id'] . '-' . time() . '.csv';

            file_put_contents('../csvs/' . $filename, $exportData);

            echo "exported to csvs/" . $filename . "\n";            
        } else {
            echo "missing 'form-id' argument.";
        }
    }
}
