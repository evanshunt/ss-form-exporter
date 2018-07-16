<?php
class ExportUserFormToCsv extends BuildTask {
    protected $title = "Export User Form data to CSV";
    protected $description = "Generates a CSV from form submissions for a given 'form-id'";

    public function run($request) {

        ini_set('memory_limit','512M');

        if(isset($_GET['form-id'])) {
            $parentID = $_GET['form-id'];

            $page = Page::get()->byID($parentID);

			$columnSQL = <<<SQL
SELECT "Name", "Title"
FROM "SubmittedFormField"
LEFT JOIN "SubmittedForm" ON "SubmittedForm"."ID" = "SubmittedFormField"."ParentID"
WHERE "SubmittedForm"."ParentID" = '$parentID'
ORDER BY "Title" ASC
SQL;
			// Sanitise periods in title
			$columns = array();
			foreach(DB::query($columnSQL)->map() as $name => $title) {
				$columns[$name] = trim(strtr($title, '.', ' '));
            }
            $columns['Created'] = 'Created';

            $submitted = $page->Submissions();

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

            $gridField = new GridField('Submission', 'Submissions', $submitted);
            $exportButton = new GridFieldExportButton();
     
            $exportButton->setExportColumns($columns);
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
