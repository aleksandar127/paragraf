<?php

?>
<!DOCTYPE html>
<html>

<head>
    <title>Osiguranja</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>


</head>

<body>

    <nav>
        <a class="brand" href="../osiguranja/index"><span>P</span>utno <span>O</span>siguranje</a>
        <ul>
            <li><a href="../osiguranja/index">Polise</a></li>
            <li><a href=" ../osiguranja/novapolisa">Nova Polisa </a> </li>
        </ul>
    </nav>
    <table id="example" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Datum unosa polise</th>
                <th>Ime i prezime</th>
                <th>Datum rodjenja</th>
                <th>Broj pasosa</th>
                <th>Email</th>
                <th>Od</th>
                <th>Do</th>
                <th>Broj dana</th>
                <th>Tip</th>

            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($this->podaci as $polise) {
                echo "<tr>";

                foreach ($polise as $k => $v) {
                    if ($k == "id" || $k == "nosilac")
                        continue;
                    echo "<td>" . $v;
                    if ($v == 'grupna') {

                        echo "<button  id='" . $polise['id'] . "'>Pregled</button>";
                    }
                }
                echo  "</td></tr>";
            } ?>

        </tbody>

    </table>


</body>



<script>
    $(document).ready(function() {
        $('#example').DataTable();
    });
</script>

<script>
    const tableBody = document.querySelector("tbody");
    const buttons = document.querySelectorAll("button");
    buttons.forEach((button) => {
        button.addEventListener("click", function(e) {

            id = this.id;
            tableBody.innerHTML = '';
            axios
                .post("../osiguranja/grupnapolisa", {
                    id
                })
                .then((response) => {
                    korisniciP = response.data;
                    korisniciP.forEach((korisnici) => {
                        let tr = document.createElement("tr");
                        let tdCreateDate = document.createElement("td");
                        tdCreateDate.textContent = korisnici.datum_kreiranja;
                        let tdName = document.createElement("td");
                        tdName.textContent = korisnici.ime;
                        let tdBirthDate = document.createElement("td");
                        tdBirthDate.textContent = korisnici.datum_rodjenja;
                        let tdPassport = document.createElement("td");
                        tdPassport.textContent = korisnici.broj_pasosa;
                        let tdEmail = document.createElement("td");
                        tdEmail.textContent = korisnici.email;
                        let tdFrom = document.createElement("td");
                        tdFrom.textContent = korisnici.datum_polaska;
                        let tdTo = document.createElement("td");
                        tdTo.textContent = korisnici.datum_dolaska;
                        let tdDays = document.createElement("td");
                        tdDays.textContent = korisnici.broj_dana;
                        let tdType = document.createElement("td");
                        tdType.textContent = korisnici.tip_polise;

                        tr.append(tdCreateDate);
                        tr.append(tdName);
                        tr.append(tdBirthDate);
                        tr.append(tdPassport);
                        tr.append(tdEmail);
                        tr.append(tdFrom);
                        tr.append(tdTo);
                        tr.append(tdDays);
                        tr.append(tdType);
                        tableBody.append(tr);
                    });
                })
                .catch((error) => {
                    console.log(error);
                });
        });
    });
</script>



<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

</body>

</html>