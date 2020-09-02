<!DOCTYPE html><?php  ?>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Novo osiguranje</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
  <link rel="stylesheet" href="../dist/css/main.css" />
</head>

<body>
  <nav>
    <a class="brand" href="<?php echo BASE.DIR ; ?>/osiguranja/index"><span>P</span>utno <span>O</span>siguranje</a>
    <ul>
      <li><a href="<?php echo BASE.DIR ; ?>/osiguranja/index">Polise</a></li>
      <li><a href="<?php echo BASE.DIR ; ?>/osiguranja/novapolisa">Nova Polisa</a></li>
    </ul>
  </nav>

  <div class="container pt-5">
    <div class="form">
      <form action="#">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="name">Ime i Prezime *</label>
              <input id="name" type="text" class="form-control" />
              <p class="error-message name text-danger"></p>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="form-group col-md-6">
            <label for="birth-date">Datum Rodjenja *</label>
            <input id="birth-date" type="date" class="form-control" />
            <p class="error-message birthDate name text-danger"></p>
          </div>
          <div class="form-group col-md-6">
            <label for="passport">Broj Pasoša *</label>
            <input id="passport" type="text" class="form-control" />
            <p class="error-message passport text-danger"></p>
          </div>
        </div>

        <div class="row">
          <div class="form-group col-md-6">
            <label for="phone">Telefon</label>
            <input id="phone" type="text" class="form-control" />
          </div>
          <div class="form-group col-md-6">
            <label for="email">Email *</label>
            <input id="email" type="text" class="form-control" />
            <p class="error-message email text-danger"></p>
          </div>
        </div>

        <div class="row">
          <label for="date-from" class="m-0 p-0 ml-3 mb-2">Datum putovanja *</label>
        </div>
        <div class="row">
          <div class="input-group col-md-6">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1">OD</span>
            </div>
            <input id="date-from" type="date" class="form-control" />
          </div>
          <div class="input-group col-md-6">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1">DO</span>
            </div>
            <input id="date-to" type="date" class="form-control" />
          </div>
          <p class="error-message travelDate text-danger ml-3 mt-1"></p>
          <p class="broj-dana col-md-12"></p>
        </div>

        <div class="row mt-1">
          <div class="form-group col-md-12">
            <label for="polisa">Vrsta Polise</label>
            <select class="form-control" name="polisa" id="policy">
              <!-- <option value="-1"></option>  -->
              <option value="individualno" selected>Individualno</option>
              <option value="grupno">Grupno</option>
            </select>
          </div>
        </div>

        <div class="dodatni">
          <div class="row">
            <div class="col-md-12">
              <h3 class="text-center">Dodatni Osiguranici</h3>
            </div>
          </div>
          <div class="row dodatni-osiguranici">
            <div class="col-md-12">
              <div class="form-group">
                <label for="imeGrupa">Ime i Prezime *</label>
                <input id="imeGrupa" type="text" class="form-control" />
                <p class="error-message error-poruka imeGrupa text-danger"></p>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="form-group col-md-6">
              <label for="datumRodjenjaGrupa">Datum Rodjenja *</label>
              <input id="datumRodjenjaGrupa" type="date" class="form-control" />
              <p class="error-message error-poruka datumRodjenjaGrupa name text-danger"></p>
            </div>
            <div class="form-group col-md-6">
              <label for="passportGrupa">Broj Pasoša *</label>
              <input id="passportGrupa" type="text" class="form-control" />
              <p class="error-message error-poruka passportGrupa text-danger"></p>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <button class="grupni-submit btn btn-sm btn-outline-light">
                DODAJ OSIGURANIKA
              </button>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <ul class="lista-dodatnih-osiguranika"></ul>
            </div>
          </div>
        </div>

        <div class="row">
          <button class="btn submit-btn mt-4 mx-auto w-50">SUBMIT</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    const passport = document.querySelector("#passport");
    passport.addEventListener("input", function() {
      if (this.value.toString().length != 9) {

        document.querySelector(".passport").textContent =
          "Pasos mora da sadrzi tacno 9 brojeva !";
      } else {
        document.querySelector(".passport").textContent = "";
      }
    });

    const passportGrupa = document.querySelector("#passportGrupa");
    passportGrupa.addEventListener("input", function() {
      if (this.value.toString().length != 9) {

        document.querySelector(".passportGrupa").textContent =
          "Pasos mora da sadrzi tacno 9 brojeva !";
      } else {
        document.querySelector(".passportGrupa").textContent = "";
      }
    });
  </script>
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script type="module" src="../dist/js/app.js"></script>
</body>

</html>