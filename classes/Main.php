<?php

/**
 * handles the the main page experiment listing and data export
 * @author artoo
 * @link https://github.com/artoo-git/---
 * @license https://opensource.org/licenses/GPL-3.0 GPLv3 License
 **/
class Main
{
    
    private $db_connection = null;
    
    private $exp_id = null;
    
    private $exp_name = "";
    
    private $exp_is_logged_in = false;
    
    public $errors = array();
    
    public $messages = array();


    public function __construct()
    {
        if(isset($_SESSION) && isset($_POST['dl'])){
            $this->pullcsv($_SESSION['exp_id']);
        }

        else if (isset($_SESSION)){
            $this->drawSummary($_SESSION['exp_id']);
            ?>
            <div class="tabs">
                <button type="button" class="tab-link active" onclick="showMainTab('new-measure', this)">Begin new measure</button>
                <button type="button" class="tab-link" onclick="showMainTab('previous-measures', this)">Previous measures</button>
            </div>
            <div id="tab-new-measure" class="tab-content active">
                <?php $this->form($_SESSION['exp_id']); ?>
            </div>
            <div id="tab-previous-measures" class="tab-content">
                <?php $this->drawMeasuresTable($_SESSION['exp_id']); ?>
            </div>
            <script>
            function showMainTab(id, btn) {
                document.querySelectorAll('.tab-content').forEach(function (el) { el.classList.remove('active'); });
                document.querySelectorAll('.tab-link').forEach(function (el) { el.classList.remove('active'); });
                document.getElementById('tab-' + id).classList.add('active');
                btn.classList.add('active');
            }
            </script>
            <?php
        }
        else{echo "smt wrong";}

    }


    private function databaseConnection()
    {
        // if connection already exists
        if ($this->db_connection != null) {
            return true;
        } else {
            try {
                $this->db_connection = new PDO('mysql:host='. DB_HOST .';dbname='. DB_NAME . ';charset=utf8', DB_USER, DB_PASS);
                return true;
            } catch (PDOException $e) {
                $this->errors[] = MESSAGE_DATABASE_ERROR . $e->getMessage();
            }
        }
        // default return
        return false;
    }

    private function pullcsv($exp_id)
    {
        $filename = "exp_" . $exp_id . " - " . date('Y-m-d') . ".csv"; 
        $delimiter = ","; 
        
        ob_end_clean();
    // Create a file pointer 
        $f = fopen('php://memory', 'w'); 
 
        // Set column headers
        $fields = array('ID', 'therapistID', 'PatientID', 'Sesssion', 'ExpCondotion', 'SessionDate', 'Status', 'MeanScore', 'PHODA_1','PHODA_2','PHODA_3','PHODA_4','PHODA_5','PHODA_6','PHODA_7','PHODA_8','PHODA_9','PHODA_10','PHODA_11','PHODA_12','PHODA_13','PHODA_14','PHODA_15','PHODA_16','PHODA_17','PHODA_18','PHODA_19','PHODA_20','PHODA_21','PHODA_22','PHODA_23','PHODA_24','PHODA_25','PHODA_26','PHODA_27','PHODA_28','PHODA_29','PHODA_30','PHODA_31','PHODA_32','PHODA_33','PHODA_34','PHODA_35','PHODA_36','PHODA_37','PHODA_38','PHODA_39','PHODA_40');
        fputcsv($f, $fields, $delimiter);

    // Get records from the database

        $result = $this->getexpData($exp_id);

        if(isset($result)){
    // Output each row of the data, format line as csv and write to file pointer
        foreach ($result as $row) {
            // still-open measures may be an active session on another device right now -
            // include them, but don't compute a mean from what could be partial data
            $isComplete = empty($row->measure_hash);
            $status = $isComplete ? 'Completed' : 'Incomplete';
            $mean = $isComplete
                ? round(($row->PHODA_1 + $row->PHODA_2 + $row->PHODA_3 + $row->PHODA_4 + $row->PHODA_5 +
                            $row->PHODA_6 + $row->PHODA_7 + $row->PHODA_8 + $row->PHODA_9 + $row->PHODA_10 +
                            $row->PHODA_11 + $row->PHODA_12 + $row->PHODA_13 + $row->PHODA_14 + $row->PHODA_15 +
                            $row->PHODA_16 + $row->PHODA_17 + $row->PHODA_18 + $row->PHODA_19 + $row->PHODA_20 +
                            $row->PHODA_21 + $row->PHODA_22 + $row->PHODA_23 + $row->PHODA_24 + $row->PHODA_25 +
                            $row->PHODA_26 + $row->PHODA_27 + $row->PHODA_28 + $row->PHODA_29 + $row->PHODA_30 +
                            $row->PHODA_31 + $row->PHODA_32 + $row->PHODA_33 + $row->PHODA_34 + $row->PHODA_35 +
                            $row->PHODA_36 + $row->PHODA_37 + $row->PHODA_38 + $row->PHODA_39 + $row->PHODA_40) / 40)
                : 'NA';
            $lineData = array($row->id, $row->thrpstID, $row->patientID, $row->session, $row->exp_cond, $row->session_date, $status, $mean, $row->PHODA_1,$row->PHODA_2,$row->PHODA_3,$row->PHODA_4,$row->PHODA_5,$row->PHODA_6,$row->PHODA_7,$row->PHODA_8,$row->PHODA_9,$row->PHODA_10,$row->PHODA_11,$row->PHODA_12,$row->PHODA_13,$row->PHODA_14,$row->PHODA_15,$row->PHODA_16,$row->PHODA_17,$row->PHODA_18,$row->PHODA_19,$row->PHODA_20,$row->PHODA_21,$row->PHODA_22,$row->PHODA_23,$row->PHODA_24,$row->PHODA_25,$row->PHODA_26,$row->PHODA_27,$row->PHODA_28,$row->PHODA_29,$row->PHODA_30,$row->PHODA_31,$row->PHODA_32,$row->PHODA_33,$row->PHODA_34,$row->PHODA_35,$row->PHODA_36,$row->PHODA_37,$row->PHODA_38,$row->PHODA_39,$row->PHODA_40);
            fputcsv($f, $lineData, $delimiter);
        }
        }
    #print_r($lineData);
 
    // Move back to beginning of file 
        fseek($f, 0); 
 
    // Set headers to download file rather than displayed 
        header('Content-Type: text/csv'); 
        header('Content-Disposition: attachment; filename="' . $filename . '";'); 
 
    // Output all remaining data on a file pointer 
        fpassthru($f); 
 
    // Exit from file 
        exit();
    }

    private function show($exp_id,$patientID)
    {
        
    }
    
    private function getexpInfo($exp_id)
    {
        // if database connection opened
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected exp
            $query_exp = $this->db_connection->prepare('SELECT * FROM experiments WHERE id = :exp_id');
            $query_exp->bindValue(':exp_id', $exp_id, PDO::PARAM_STR);
            $query_exp->execute();
            #$query_exp->debugDumpParams();
            #print_r($query_exp->fetchObject());
            // get result row (as an object)
            $results = $query_exp->fetchObject();
            return $results;
        } else {
            return false;
        }
    }

    private function getexpData($exp_id)
    {
        // if database connection opened
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected exp
            $query_data = $this->db_connection->prepare('SELECT * FROM data WHERE exp_id = :exp_id');
            $query_data->bindValue(':exp_id', $exp_id, PDO::PARAM_STR);
            $query_data->execute();
            #$query_exp->debugDumpParams();
            #print_r($query_exp->fetchObject());
            // get result rows (as an object)
            return $query_data->fetchAll(PDO::FETCH_OBJ);
            //return $query_data->fetchall(PDO::FETCH_GROUP);
            
        } else {
            return false;
        }
    }

    private function getphoda($measureID)
    {
        // if database connection opened
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected exp
            $query_data = $this->db_connection->prepare('SELECT * FROM data WHERE id = :id');
            $query_data->bindValue(':id', $measureID, PDO::PARAM_STR);
            $query_data->execute();
            #$query_exp->debugDumpParams();
            #print_r($query_exp->fetchObject());
            // get result rows (as an object)
            return $query_data->fetchAll(PDO::FETCH_OBJ);
            //return $query_data->fetchall(PDO::FETCH_GROUP);
            
        } else {
            return false;
        }
    }

    private function drawSummary($exp_id)
    {
        if ($this->getexpInfo($exp_id) != NULL){
            $summaryA = $this->getexpInfo($exp_id);?>
            <div class="summary-row">
            <table class="summary">
            <tr><th colspan="2">Summary</th>
            <tr><td>Experiment name: </td>
                <td><?php echo $summaryA->exp_name;?></td>
            </tr>
            <tr><td>Created on: </td>
                <td><?php echo $summaryA->exp_registration_datetime;?></td>
            </tr>
            <tr><td colspan="2" style="border-top: 1px solid"></td></tr>

            </table>
            <?php if ($this->getexpData($exp_id) != NULL) { ?>
            <form method="POST" action="index.php">
            <button class="button" name="dl" onclick="ajaxDl()" value="submit">Download CSV</button>
            </form>
            <?php } ?>
            </div>
            <?php
        }
    }

    private function drawMeasuresTable($exp_id)
    {
        if ($this->getexpData($exp_id) != NULL){

            $summaryB = $this->getexpData($exp_id);
            ?>
            <div class="measures-scroll">
            <table class="measures">
            <thead>
            <tr>
                <th onclick="sortMeasures(0, this)">Patient ID</th>
                <th onclick="sortMeasures(1, this)">Therapist ID</th>
                <th onclick="sortMeasures(2, this)">Session</th>
                <th onclick="sortMeasures(3, this)">Condition</th>
                <th onclick="sortMeasures(4, this)">Date</th>
                <th onclick="sortMeasures(5, this)">Status</th>
                <th onclick="sortMeasures(6, this)">Mean score</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($summaryB as $data) {
                // a still-open measure may be an active session on another device right
                // now - show it, but don't compute a mean from what could be partial data
                $isComplete = empty($data->measure_hash);
                $status = $isComplete ? 'Completed' : 'Incomplete';
                $mean = $isComplete
                    ? round(($data->PHODA_1 + $data->PHODA_2 + $data->PHODA_3 + $data->PHODA_4 + $data->PHODA_5 +
                                $data->PHODA_6 + $data->PHODA_7 + $data->PHODA_8 + $data->PHODA_9 + $data->PHODA_10 +
                                $data->PHODA_11 + $data->PHODA_12 + $data->PHODA_13 + $data->PHODA_14 + $data->PHODA_15 +
                                $data->PHODA_16 + $data->PHODA_17 + $data->PHODA_18 + $data->PHODA_19 + $data->PHODA_20 +
                                $data->PHODA_21 + $data->PHODA_22 + $data->PHODA_23 + $data->PHODA_24 + $data->PHODA_25 +
                                $data->PHODA_26 + $data->PHODA_27 + $data->PHODA_28 + $data->PHODA_29 + $data->PHODA_30 +
                                $data->PHODA_31 + $data->PHODA_32 + $data->PHODA_33 + $data->PHODA_34 + $data->PHODA_35 +
                                $data->PHODA_36 + $data->PHODA_37 + $data->PHODA_38 + $data->PHODA_39 + $data->PHODA_40) / 40)
                    : 'NA';
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($data->patientID); ?></td>
                    <td><?php echo htmlspecialchars($data->thrpstID); ?></td>
                    <td><?php echo htmlspecialchars($data->session); ?></td>
                    <td><?php echo htmlspecialchars($data->exp_cond); ?></td>
                    <td><?php echo htmlspecialchars($data->session_date); ?></td>
                    <td><?php echo $status; ?></td>
                    <td><?php echo $mean; ?></td>
                </tr>
            <?php } ?>
            </tbody>
            </table>
            </div>
            <script>
            function sortMeasures(colIndex, th) {
                var table = th.closest('table');
                var tbody = table.querySelector('tbody');
                var rows = Array.prototype.slice.call(tbody.querySelectorAll('tr'));
                var ascending = th.getAttribute('data-sort-dir') !== 'asc';

                function isNumeric(text) {
                    return text !== '' && !isNaN(Number(text));
                }

                rows.sort(function (a, b) {
                    var aText = a.children[colIndex].textContent.trim();
                    var bText = b.children[colIndex].textContent.trim();
                    var aNumeric = isNumeric(aText);
                    var bNumeric = isNumeric(bText);
                    var cmp;
                    if (aNumeric && bNumeric) {
                        cmp = Number(aText) - Number(bText);
                    } else if (aNumeric !== bNumeric) {
                        // one side is a non-numeric placeholder like "NA" - always sort it last
                        cmp = aNumeric ? -1 : 1;
                    } else {
                        cmp = aText.localeCompare(bText);
                    }
                    return ascending ? cmp : -cmp;
                });

                table.querySelectorAll('th').forEach(function (header) {
                    header.classList.remove('sorted-asc', 'sorted-desc');
                    if (header !== th) header.removeAttribute('data-sort-dir');
                });
                th.setAttribute('data-sort-dir', ascending ? 'asc' : 'desc');
                th.classList.add(ascending ? 'sorted-asc' : 'sorted-desc');

                rows.forEach(function (row) { tbody.appendChild(row); });
            }
            </script>
            <?php
        }
    }

     private function form($exp_id)
    {
        
        ?>
        <p>
        <form autocomplete="off" method="POST" action="exp.php">
        <table class="experiment">
        
        <tr><td>Participant ID</td>
            <td><div class="autocomplete" style="max-width:300px;"><input id="patientID" type="text" name="patientID" placeholder="Pseudonym" required></td>
            </div></tr>
        <tr><td>Therapist ID</td>
            <td><div class="autocomplete" style="max-width:300px;"><input id="thrpstID" type="text" name="thrpstID" placeholder="Therapist" required> </td>
            </div></tr>
        <tr><td>Session</td>
            <td><input id="session" type="text" name="session" placeholder="Session number" required></td>
            </tr>
        <tr><td>Condition code</td>
            <td><div class="autocomplete" style="max-width:300px;"><input id="condition" type="text" name="condition" placeholder="Condition" required></td>
            </div></tr>
       
        <tr><td></td><td>
            <input type="hidden" name="NewTest" value="1">
            <button class="button" type="submit" value="submit">Begin new measure</</button></td></tr>
        
        </table>
        </form>
        </p><?php

        if ($this->getexpData($exp_id) != NULL){
            // create empty arrays for the ajax autocomplete
            $subjects = array();
            $therapists = array();
            $conditions = array();
            $data = $this->getexpData($exp_id);
            foreach ($data as $measure) {
                // get all patientIDs for this experiments that have been tested
                if (!in_array($measure->patientID, $subjects)){
                    // get all therapists that have worked in this experiment
                    if(isset($measure->patientID)){
                    $subjects[] = $measure->patientID;
                    }
                }
                if (!in_array($measure->thrpstID, $therapists)){
                    // get all therapists that have worked in this experiment
                    if(isset($measure->thrpstID)){
                    $therapists[] = $measure->thrpstID;
                    }
                }
                //
                if (!in_array($measure->exp_cond, $conditions)){
                    // get all condition descriptions that were used in this exp
                    if(isset($measure->exp_cond)){
                        $conditions[] = $measure->exp_cond;
                    }
                }
            }
        }?>
            <script src="js/ajax.js"></script>
            <script>
            var subjects = <?php echo json_encode($subjects); ?>;
            var therapists = <?php echo json_encode($therapists); ?>;
            var conditions = <?php echo json_encode($conditions); ?>;
            autocomplete(document.getElementById("patientID"), subjects);
            autocomplete(document.getElementById("thrpstID"), therapists);
            autocomplete(document.getElementById("condition"), conditions);
            </script>
            <?php
    }

    
}