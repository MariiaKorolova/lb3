<?php

class DbAccess
{
    private PDO $db;

    public function __construct()
    {
        $this->db = new PDO("mysql:host=127.0.0.1;dbname=traffic", "root", "");
    }

    public function showClients(): void
    {
        $statement = $this->db->query("SELECT DISTINCT `name` FROM client");
        while ($data = $statement->fetch()) {
            echo "<option value='$data[0]'>$data[0]</option>";
        }
    }

    public function statisticByClient($client): void
    {
        $statement = $this->db->prepare("SELECT name, `start`, stop, in_traffic, out_traffic FROM seanse inner join client on FID_Client = ID_Client WHERE `name`=?");
        $statement->execute([$client]);
        $txt = "<table border=1>";
        $txt .= " <tr>
         <th>Name</th>
         <th> Start time  </th>
         <th> Stop time </th>
         <th> In Traffic </th>
         <th> Out Traffic </th>
        </tr> ";
        while ($data = $statement->fetch()) {
            $txt .= " <tr>
             <td> {$data['name']} </td>
             <td> {$data['start']}  </td>
             <td> {$data['stop']} </td>
             <td> {$data['in_traffic']} </td>
             <td> {$data['out_traffic']} </td>
            </tr> ";
        }
        $txt .= "</table>";
        echo $txt;
    }

    public function statisticByTime($start, $stop): void
    {
        $statement = $this->db->prepare("
        SELECT `name`, `start`, stop, in_traffic, out_traffic 
        FROM seanse inner join client on FID_Client = ID_Client 
        WHERE `start` BETWEEN :start_date AND :stop OR `stop` BETWEEN :start_date AND :stop
");
        $statement->execute(["start_date" => $start, "stop" => $stop]);
        $txt = "<table border=1>";
        $txt .= " <tr>
         <th> Name  </th>
         <th> Start time  </th>
         <th> Stop time </th>
         <th> In Traffic </th>
         <th> Out Traffic </th>
        </tr> ";
        while ($data = $statement->fetch()) {
            $txt .= " <tr>
             <td> {$data['name']}  </td>
             <td> {$data['start']}  </td>
             <td> {$data['stop']} </td>
             <td> {$data['in_traffic']} </td>
             <td> {$data['out_traffic']} </td>
            </tr> ";
        }
        $txt .= "</table>";
        echo json_encode($txt);
    }

    public function balance(): void
    {
        $statement = $this->db->prepare("SELECT `name`, login, password, IP, balance FROM client WHERE balance < 0");
        $statement->execute();
        $txt = "<table border=1>";
        $txt .= " <tr>
         <th> Name  </th>
         <th> Login  </th>
         <th> Password </th>
         <th> IP </th>
         <th> Balance </th>
        </tr> ";
        while ($data = $statement->fetch()) {
            $txt .= " <tr>
             <td> {$data['name']}  </td>
             <td> {$data['login']}  </td>
             <td> {$data['password']} </td>
             <td> {$data['IP']} </td>
             <td> {$data['balance']} </td>
            </tr> ";
        }
        $txt .= "</table>";
        echo $txt;
    }
}

$dbAccess = new DbAccess();

if (isset($_POST["client"])) {
    $dbAccess->statisticByClient($_POST["client"]);
} elseif (isset($_POST["start"])) {
    $dbAccess->statisticByTime($_POST["start"], $_POST["stop"]);
} elseif (isset($_POST["balance"])) {
    $dbAccess->balance();
}