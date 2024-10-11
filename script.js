function sendMail(){
    let parms={
        name:document.getElementById("").value,
        email:document.getElementById("email").value,
        subject:document.getElementById("subject").value,
        message:document.getElementById("message").value
    }
    emailjs.send("service_yfjgvp2" ,"template_m861a3t",parms).then(alert("email sent!!"))
}
<nav>
            <ul>
                
                <li><a href="index.html">Accueil</a></li>
                <li><a href="list.php">Liste d'invités</a></li>
            </ul>
        </nav>