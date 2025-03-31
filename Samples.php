<?php require 'navbar.php'; ?>
<?php
session_start();
include('database.php'); 

if (isset($_SESSION['user_id'])) {
    $user_id = intval($_SESSION['user_id']);
    
    $query = "SELECT admin FROM usuarios WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if (isset($row['admin']) && trim($row['admin']) === 'admin123') {
            if (basename($_SERVER['PHP_SELF']) !== 'MusicaAdmin.php') { // Evita redirecionamento se já estiver na página
                header("Location: MusicaAdmin.php");
                exit();
            }
        }
    }
    mysqli_stmt_close($stmt);
}

?>
<br>
<br>
<br>
<body style="background-color: #181818">  
    <div class="beats-container">
        <!-- Exemplo de beat -->
        <div class="beat">
            <div class="beat-info">
                <h3>Trap</h3>
                <p>BPM: 120-160 </p>
                <p style="font-size: 13px;">Modern hip-hop, heavy beats with fast hi-hats and low 808s.</p>
                <?php if (!isset($_SESSION['user_id'])) { ?>
                <a href="login.php">Listen Now</a>
                <?php } else { ?>
                <a href="Trap.php">Listen Now</a>
                <?php } ?>
            </div>
        </div>
        <div class="beat">
            <div class="beat-info">
                <h3>Boom Bap</h3>
                <p>BPM: 85-95 </p>
                <p style="font-size: 13px;">Classic hip-hop, with soul/jazz samples and simple, striking beats.</p>
                <?php if (!isset($_SESSION['user_id'])) { ?>
                <a href="login.php">Listen Now</a>
                <?php } else { ?>
                <a href="Boom.php">Listen Now</a>
                <?php } ?>
            </div>
        </div>
        <div class="beat">
            <div class="beat-info">
                <h3>Lo-fi Hip Hop </h3>
                <p>BPM: 60-90 </p>
                <p style="font-size: 13px;">Relaxing, vintage, with melodic sounds and smooth textures.</p>
                <?php if (!isset($_SESSION['user_id'])) { ?>
                <a href="login.php">Listen Now</a>
                <?php } else { ?>
                <a href="Lo.php">Listen Now</a>
                <?php } ?>
            </div>
        </div>


        <!-- Adicione mais beats aqui -->
    </div>
    <div class="margin-top">
    <div class="beats-container">
        <!-- Exemplo de beat -->
        <div class="beat"> 
            <div class="beat-info">
                <h3>Drill </h3>
                <p>BPM: 130-145 </p>
                <p style="font-size: 13px;">Dark and aggressive, with stutter hi-hats and distorted 808s.</p>
                <?php if (!isset($_SESSION['user_id'])) { ?>
                <a href="login.php">Listen Now</a>
                <?php } else { ?>
                <a href="Drill.php">Listen Now</a>
                <?php } ?>
            </div>
        </div>
        <div class="beat">
            <div class="beat-info">
                <h3>Afrobeat</h3>
                <p>BPM: 100-120 </p>
                <p style="font-size: 13px;">African rhythms with synthesizers and striking percussion.</p>
                <?php if (!isset($_SESSION['user_id'])) { ?>
                <a href="login.php">Listen Now</a>
                <?php } else { ?>
                <a href="Afro.php">Listen Now</a>
                <?php } ?>
            </div>
        </div>
        <div class="beat">
            <div class="beat-info">
                <h3>Dancehall </h3>
                <p>BPM: 95-110 </p>
                <p style="font-size: 13px;">Jamaican music, catchy beats with a strong backbeat.</p>
                <?php if (!isset($_SESSION['user_id'])) { ?>
                <a href="login.php">Listen Now</a>
                <?php } else { ?>
                <a href="Dan.php">Listen Now</a>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="margin-top">
    <div class="beats-container">
        <!-- Exemplo de beat -->
        <div class="beat"> 
            <div class="beat-info">
                <h3>Boom Bap Lo-Fi</h3>
                <p>BPM: 70-85 </p>
                <p style="font-size: 13px;">Combination of boom bap nostalgia with smooth lo-fi elements.</p>
                <?php if (!isset($_SESSION['user_id'])) { ?>
                <a href="login.php">Listen Now</a>
                <?php } else { ?>
                <a href="Boomlo.php">Listen Now</a>
                <?php } ?>
            </div>
        </div>
        <div class="beat">
            <div class="beat-info">
                <h3>Reggaeton </h3>
                <p>BPM: 85-100 </p>
                <p style="font-size: 13px;">Latin music with rhythmic dembow beats.</p>
                <?php if (!isset($_SESSION['user_id'])) { ?>
                <a href="login.php">Listen Now</a>
                <?php } else { ?>
                <a href="Ton.php">Listen Now</a>
                <?php } ?>
            </div>
        </div>
        <div class="beat">
            <div class="beat-info">
                <h3>EDM </h3>
                <p>BPM: 120-150 </p>
                <p style="font-size: 13px;">Electronic, with subgenres such as house, techno, dubstep and trap EDM.</p>
                <?php if (!isset($_SESSION['user_id'])) { ?>
                <a href="login.php">Listen Now</a>
                <?php } else { ?>
                <a href="EDM.php">Listen Now</a>
                <?php } ?>
            </div>
        </div>
    </div>
    </div>
    <div class="margin-top">
    <div class="beats-container">
        <div class="beat"> 
            <div class="beat-info">
                <h3>R&B </h3>
                <p>BPM: 60-90 </p>
                <p style="font-size: 13px;">Smooth and melodic sound, with a focus on vocals and harmonic grooves.</p>
                <?php if (!isset($_SESSION['user_id'])) { ?>
                <a href="login.php">Listen Now</a>
                <?php } else { ?>
                <a href="R&B.php">Listen Now</a>
                <?php } ?>
            </div>
        </div>
        <div class="beat">
            <div class="beat-info">
                <h3>Other</h3>
                <p>BPM: Miscellaneous </p>
                <p style="font-size: 13px;">Other types of Samples that are not included in the options above </p>
                <?php if (!isset($_SESSION['user_id'])) { ?>
                <a href="login.php">Listen Now</a>
                <?php } else { ?>
                <a href="Outro.php">Listen Now</a>
                <?php } ?>
            </div>
        </div>
    </div>
    </div>

    <footer class="footer">
    <p>&copy; 2025 BeatStation. All rights reserved.
    </p>
       <p>Follow us: 
           <a href="https://www.instagram.com/beatstationweb/">Instagram</a> | 
           <a href="https://x.com/BeatStationWeb">Twitter</a> | 
           <a href="https://www.facebook.com/profile.php?id=61573697442314">Facebook</a>
       </p>
   </footer>
</body>
</html>
