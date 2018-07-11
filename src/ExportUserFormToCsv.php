<?php
namespace EvansHunt\SsFormExporter;

class ExportUserFormToCsv extends BuildTask {
    protected $title = "Export User Form data to CSV";
    protected $description = "Generates a CSV from form submissions for a given 'form-id'";

    public function run($request) {
        if($_GET['form-id']) {
            $submitted = SubmittedForm::get()->filter(['ParentID' => $_GET['form-id']]);
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

            file_put_contents('../csvs/contact-submissions.csv', $exportData);

            echo "exported to csvs/contact-submissions.csv\n";            
        } else {
            echo "missing 'form-id' argument.";
        }
    }
}
